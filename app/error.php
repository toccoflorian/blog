<?php
require_once __DIR__ . '/database/database.php';
$AuthDB = require_once __DIR__ . '/database/security.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/index.css">
    <title>Blog</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <h1 style="font-size: 30px;text-align: center;margin-top: 15%;">Oops... Une erreur s'est produite.</h1>

        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>