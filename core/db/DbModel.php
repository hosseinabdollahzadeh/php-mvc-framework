<?php

namespace app\core\db;

use app\core\Application;
use app\core\Model;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;

    abstract public function attributes(): array;

    abstract public static function primaryKey(): string;

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
            $primaryKey = $this->getPrimaryKey();
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

    public static function findOne($where) // [ email => test@example.com, first_name => hossein ]
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare(" SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}