<?php

namespace Database;

use PDO;

class Database
{
    private static ?Database $instance = null;
    private readonly PDO $pdo;

    private function __construct()
    {
        $this->pdo = new PDO("sqlite:" . DB_FILE);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}