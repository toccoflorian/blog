<?php
require_once __DIR__ . '/database/database.php';
$AuthDB = require_once __DIR__ . '/database/security.php';
$currentUser = $AuthDB->islogged();
$articleDB = require_once __DIR__ . '/database/models/articleDB.php';
$articles = $articleDB->fetchAll();
$categories = [];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? '';

if ($articles) {
    # compter articles par categorie
    $catmp = array_map(fn ($a) => $a['category'],  $articles);
    $categories = array_reduce($catmp, function ($acc, $cat) {
        if (isset($acc[$cat])) {
            $acc[$cat]++;
        } else {
            $acc[$cat] = 1;
        }
        return $acc;
    }, []);
    # trier par categorie
    $articlePerCategories = array_reduce($articles, function ($acc, $article) {
        if (isset($acc[$article['category']])) {
            $acc[$article['category']] = [...$acc[$article['category']], $article];
        } else {
            $acc[$article['category']] = [$article];
        }
        return $acc;
    }, []);
};
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
        <h1>Inscrivez vous pour éditer un article.</h1>

        <div class="content">

            <div class="newsfeed-container">

                <ul class="category-container">
                    <li class=<?= $selectedCat ? '' : 'cat-active' ?>><a href="/">Tous les articles <span class="small">(<?= count($articles) ?>)</span></a></li>

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
                                        <p class="author">Auteur: <b><?= $a['firstname'] . ' ' . $a['lastname'] ?></b></p>
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