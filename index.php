<?php 
require_once __DIR__ . '/vendor/autoload.php';

// Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dbHost = $_ENV['UPG_AUTH_USERNAME'];

$request_method = $_SERVER['REQUEST_METHOD'];

if($request_method == 'POST'){
    echo 'I am here';
}
?>