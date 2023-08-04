<?php

$dsn = 'mysql:host=127.0.0.1;dbname=blog2';
$dbUser = getenv('DB_USER');
$mdp = getenv('DB_PWD');

try {
    $pdo = new PDO($dsn, $dbUser, $mdp, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $exception) {
    throw new Exception($exception->getMessage());
}



return $pdo;
