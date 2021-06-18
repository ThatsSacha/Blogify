<?php
require 'vendor/autoload.php';
require 'Controller/RouterController.php';

$dotenv = Dotenv\Dotenv::createMutable(__DIR__);
$dotenv->load();