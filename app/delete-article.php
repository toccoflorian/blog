<?php
require_once __DIR__ . '/database/database.php';
$AuthDB = require_once __DIR__ . '/database/security.php';
if (!$AuthDB->islogged()) {
    header('location:blog/auth-login.php');
}
$articleDB = require_once __DIR__ . '/database/models/articleDB.php';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
if (!$id) {
    header('Location: /blog/');
} else {
    $articleDB->deleteOne($id);
    header('Location: /blog/');
}
