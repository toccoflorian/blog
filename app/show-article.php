<?php
require_once __DIR__ . '/app/database/database.php';
$AuthDB = require_once __DIR__ . 'app/database/security.php';
$currentUser = $AuthDB->islogged();
$articleDB = require_once __DIR__ . '/database/models/articleDB.php';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

$article = $articleDB->fetchOne($id);

if (!$id) {
    header('Location: /');
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/show-article.css">
    <title>Article</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="article-container">
                <a class="article-back" href="/">Retour à la liste de tous articles</a>
                <?php if ($currentUser) : ?>
                    <a class="article-back" href="profile.php">Mes articles</a>
                <?php endif ?>
                <div class="article-cover-img" style="background-image:url(<?= $article['image'] ?>)"></div>
                <h1 class="article-title"><?= $article['title'] ?></h1>
                <div class="separator"></div>
                <p class="article-content"><?= $article['content'] ?></p>
                <p class="author">Auteur: <b><?= $article['firstname'] . ' ' . $article['lastname'] ?></b></p>

                <div class="action">
                    <?php if ($currentUser && $currentUser['id'] === $article['author']) : ?>
                        <a class="btn btn-secondary" href="/delete-article.php?id=<?= $article['id'] ?>">Supprimer</a>
                        <a class="btn btn-primary" href="/form-article.php?id=<?= $article['id'] ?>">Editer l'article</a>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>