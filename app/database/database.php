<?php

require_once __DIR__ . '../../vendor/autoload.php';  // ajuste le chemin selon ta structure de projet

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dsn = "mysql:host=164.132.229.216;dbname=blogPHP";
$dbUser = getenv("DBuser");
$mdp = getenv("DBmdp");
// $dbUser = getenv('DB_USER');
// $mdp = getenv('DB_PWD');



try {
    $pdo = new PDO($dsn, $dbUser, $mdp, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $exception) {
    throw new Exception($exception->getMessage());
}



return $pdo;
