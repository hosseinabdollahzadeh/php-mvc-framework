<?php

use abiz\phpmvc\Application;

class m0002_add_password_column_to_users_table
{
    public function up()
    {
        $db = Application::$app->db;
        $db->pdo->exec("ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL AFTER last_name;");
    }

    public function down()
    {
        $db = Application::$app->db;
        $db->pdo->exec("ALTER TABLE users DROP COLUMN password;");
    }
}