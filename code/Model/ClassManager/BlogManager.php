<?php

namespace App\ClassManager;

use PDO;

class BlogManager {
    private PDO $db;

    public function __construct()
    {
        $db = new DatabaseManager();
        $this->db = $db->connect();
    }

    public function findAll() {
        var_dump($this->db);
    }
}