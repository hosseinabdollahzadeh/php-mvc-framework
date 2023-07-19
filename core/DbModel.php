<?php

namespace app\core;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);

        if ($this->isNewRecord()) {
            // Insert new model
            $statement = self::prepare("INSERT INTO $tableName (" . implode(",", $attributes) . ") 
            VALUES (" . implode(",", $params) . ")");
        } else {
            // Update existing model
            $primaryKey = $this->primaryKey();
            $updateFields = array_map(fn($attr) => "$attr=:$attr", $attributes);
            $statement = self::prepare("UPDATE $tableName SET " . implode(",", $updateFields) . " WHERE $primaryKey = :$primaryKey");
            $statement->bindValue(":$primaryKey", $this->{$primaryKey});
        }

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}