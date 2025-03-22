<?php

require_once realpath(__DIR__ . "/vendor/autoload.php");

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

 $servername = $_ENV['DB_HOST'];
 $username = $_ENV['DB_USER_NAME'];
 $password = $_ENV['DB_PASSWORD'];
 $db = $_ENV['DB'];

 /**
  * @var mysqli $conn
  */

 $conn = new mysqli($servername, $username, $password, $db);


