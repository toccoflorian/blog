<?php
require_once __DIR__ . '/database/database.php';
$AuthDB = require_once __DIR__ . '/database/security.php';
$currentUser = $AuthDB->islogged();
if (!$currentUser) {
    header('location:auth-login.php');
}
$DBArticles = require_once __DIR__ . '/database/models/articleDB.php';
$articlesByAuthor = $DBArticles->fetchAllByAuthor($currentUser['id']);
$selectedCat = $_GET['cat'] ?? '';
$catmp = array_map(fn ($a) => $a['category'],  $articlesByAuthor);
$categories = array_reduce($catmp, function ($acc, $cat) {
    if (isset($acc[$cat])) {
        $acc[$cat]++;
    } else {
        $acc[$cat] = 1;
    }
    return $acc;
}, []);
$articlePerCategories = array_reduce($articlesByAuthor, function ($acc, $articlesByAuthor) {
    if (isset($acc[$articlesByAuthor['category']])) {
        $acc[$articlesByAuthor['category']] = [...$acc[$articlesByAuthor['category']], $articlesByAuthor];
    } else {
        $acc[$articlesByAuthor['category']] = [$articlesByAuthor];
    }
    return $acc;
}, []);
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/profile.css">
    <title>Profil</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <h1>Mes articles</h1>
        <p><?= $currentUser['firstname'] . ' ' . $currentUser['lastname'] . ' ' . $currentUser['email'] ?></p>

        <div class="content">

            <div class="newsfeed-container">
                <ul class="category-container">
                    <li class=<?= $selectedCat ? '' : 'cat-active' ?>><a href="/">Tous les articles <span class="small">(<?= count($articlesByAuthor) ?>)</span></a></li>
                    <?php foreach ($categories as $catName => $catNum) : ?>
                        <li class=<?= $selectedCat ===  $catName ? 'cat-active' : '' ?>><a href="/?cat=<?= $catName ?>"> <?= $catName ?><span class="small">(<?= $catNum ?>)</span> </a></li>
                    <?php endforeach; ?>
                </ul>
                <div class="newsfeed-content">
                    <?php if (!$selectedCat) : ?>
                        <?php foreach ($categories as $cat => $num) : ?>
                            <h2><?= $cat ?></h2>
                            <div class="articles-container">
                                <?php foreach ($articlePerCategories[$cat] as $a) : ?>
                                    <a href="/show-article.php?id=<?= $a['id'] ?>" class="article block">
                                        <div class="overflow">
                                            <div class="img-container" style="background-image:url(<?= $a['image'] ?>"></div>
                                        </div>
                                        <h3><?= $a['title'] ?></h3>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <h2><?= $selectedCat ?></h2>
                        <div class="articles-container">
                            <?php foreach ($articlePerCategories[$selectedCat] as $a) : ?>
                                <a href="/show-article.php?id=<?= $a['id'] ?>" class="article block">
                                    <div class="overflow">
                                        <div class="img-container" style="background-image:url(<?= $a['image'] ?>"></div>
                                    </div>
                                    <h3><?= $a['title'] ?></h3>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>






        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>