<?php
namespace App\Controller;
use App\ClassManager\BlogManager;

class BlogController {
    private $blogManager;

    public function __construct() {
        echo 'hey';
        $this->blogManager = new BlogManager();
    }

    public function findAll() {
        echo 'llllll';
        var_dump($this->blogManager->findAll());
    }
}