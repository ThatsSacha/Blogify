<?php
namespace App\Controller;
use App\ClassManager\BlogManager;
use App\ClassManager\DatabaseManager;

$blogManager = new BlogManager();

$blogManager->findAll();