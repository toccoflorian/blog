<?php
$AuthDB = require_once __DIR__ . 'app/database/security.php';
$sessionId = $_COOKIE['session'] ?? '';
if (!$sessionId) {
    header('location:/');
} else {
    $AuthDB->logout();
}
