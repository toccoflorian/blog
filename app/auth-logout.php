<?php
$AuthDB = require_once __DIR__ . '/database/security.php';
$sessionId = $_COOKIE['session'] ?? '';
if (!$sessionId) {
    header('location:/blog/');
} else {
    $AuthDB->logout();
}
