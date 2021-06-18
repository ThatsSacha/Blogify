<?php

namespace App\ClassManager;
use App\Class\Database;
use PDO;

require '../../secret.php';

class DatabaseManager {
    public function connect(): PDO {
        return new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname= ' . $_ENV['DB_NAME'] . ';charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    }
}