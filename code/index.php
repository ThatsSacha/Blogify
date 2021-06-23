<?php

require 'vendor/autoload.php';
use App\Controller\RouterController;

$dotenv = Dotenv\Dotenv::createMutable(__DIR__);
$dotenv->load();

$r = new RouterController();