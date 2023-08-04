<?php
$AuthDB = require_once 'database/security.php';
$sessionId = $_COOKIE['session'] ?? '';
if (!$sessionId) {
    header('location:/');
} else {
    $AuthDB->logout();
}
