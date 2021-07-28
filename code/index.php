<?php

require 'vendor/autoload.php';
setlocale(LC_TIME, 'fr_FR');
use App\Controller\RouterController;
session_start();

$dotenv = Dotenv\Dotenv::createMutable(__DIR__);
$dotenv->load();

$r = new RouterController();