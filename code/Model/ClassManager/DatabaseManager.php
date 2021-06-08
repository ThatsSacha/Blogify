<?php

namespace App\ClassManager;
use App\Class\Database;
require '../../secret.php';

class DatabaseManager {
    public function connect(): void {
        $database = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
    }
}