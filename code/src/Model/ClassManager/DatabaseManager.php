<?php

namespace App\Model\ClassManager;
use PDO;
use Exception;
use PDOException;
use App\Class\Database;
use PDOStatement;

class DatabaseManager {
    public function connect(): PDO|PDOException {
        try {
            return new PDO('mysql:host=127.0.0.1;dbname=' . $_ENV['DB_NAME'] . ';charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        } catch(PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}