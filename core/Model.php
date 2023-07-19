<?php

namespace app\core;

abstract class Model
{
    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_MIN = 'min';
    const RULE_MAX = 'max';
    const RULE_MATCH = 'match';
    const RULE_UNIQUE = 'unique';

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public array $errors = [];

    abstract public function rules(): array;

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($rule)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    // Check if the attribute value has changed for an existing record
                    if (!$this->isNewRecord() && $this->isAttributeChanged($attribute, $tableName)) {
                        $primaryKey = $this->getPrimaryKey();
                        $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr AND $primaryKey != :id");
                        $statement->bindValue(":attr", $value);
                        $statement->bindValue(":id", $this->{$this->getPrimaryKey()});
                    } else {
                        $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                        $statement->bindValue(":attr", $value);
                    }
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    private function addErrorForRule(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Min length of this field must be {max}',
            self::RULE_MATCH => 'This field must be he same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public function isNewRecord()
    {
        return empty($this->{$this->getPrimaryKey()});
    }

    public function getPrimaryKey(): string|bool
    {
        return $this->primaryKey() ?? false;
    }

    public function isAttributeChanged($attribute, $table)
    {
        // Get the original attribute value from the database
        $originalValue = $this->getOriginalAttributeValue($attribute, $table);

        // Get the current attribute value
        $currentValue = $this->{$attribute};

        // Compare the original and current values to check for changes
        return $originalValue !== $currentValue;
    }

    private function getOriginalAttributeValue($attribute, $table)
    {
        // Assuming there is a primary key named 'id' for the model
        $primaryKey = $this->getPrimaryKey();

        // Check if the model has been saved to the database
        if ($this->isNewRecord()) {
            return null; // No original value for new records
        }

        // Retrieve the original attribute value from the database
        $statement = Application::$app->db->prepare("SELECT $attribute FROM $table WHERE $primaryKey = :id");
        $statement->bindValue(":id", $this->{$this->getPrimaryKey()});
        $statement->execute();
        $record = $statement->fetchObject();

        // Return the original attribute value or null if it cannot be determined
        return $record?->{$attribute};
    }
}