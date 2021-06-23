<?php

namespace App\ClassManager;

use PDO;

class BlogManager {
    private PDO $db;

    public function __construct()
    {
        
    }

    public function findAll() {
        var_dump('coucou');
    }
}