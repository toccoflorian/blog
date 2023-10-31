<?php
require_once __DIR__ . '/database/database.php';
$AuthDB = require_once __DIR__ . '/database/security.php';
$currentUser = $AuthDB->islogged();
if (!$currentUser) {
    header('location:auth-login.php');
}
$articleDB = require_once __DIR__ . '/database/models/articleDB.php';

const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_TITLE_TOO_SHORT = 'Le titre est trop court';
const ERROR_CONTENT_TOO_SHORT = 'L\'article est trop court';
const ERROR_IMAGE_URL = 'L\'image doit être une url valide';


$errors = [
    'title' => '',
    'image' => '',
    'category' => '',
    'content' => ''
];


$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if ($id) {

    $article = $articleDB->fetchOne($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_POST = filter_input_array(INPUT_POST, [
        'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'image' => FILTER_SANITIZE_URL,
        'category' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);

    $title = $_POST['title'] ?? '';
    $image = $_POST['image'] ?? '';
    $category = $_POST['category'] ?? '';
    $content = $_POST['content'] ?? '';

    $article = [
        'title' => $title,
        'image' => $image,
        'content' => $content,
        'category' => $category,
        'id' => $id,
        'author' => $currentUser['id']
    ];

    if (!$title) {
        $errors['title'] = ERROR_REQUIRED;
    } elseif (mb_strlen($title) < 5) {
        $errors['title'] = ERROR_TITLE_TOO_SHORT;
    }

    if (!$image) {
        $errors['image'] = ERROR_REQUIRED;
    } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors['image'] = ERROR_IMAGE_URL;
    }

    if (!$category) {
        $errors['category'] = ERROR_REQUIRED;
    }

    if (!$content) {
        $errors['content'] = ERROR_REQUIRED;
    } elseif (mb_strlen($content) < 30) {
        $errors['content'] = ERROR_CONTENT_TOO_SHORT;
    }
    # si pas d'erreur
    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        # si on a reçu un id (modifier article)
        if ($id) {
            $articleDB->updateOne($article);
        } else { # ajouter un article
            $articleDB->createOne($article);
        }
        header('Location: /');
    }
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/blog/public/css/formulaire.css">
    <title><?= $id ? 'Modifier' : 'Créer' ?> un article</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="block p-20 form-container">
                <h1><?= $id ? 'Modifier' : 'Éditer' ?> un article</h1>
                <form action="/blog/form-article.php<?= $id ? "?id=$id" : '' ?>" , method="POST">
                    <div class="form-control">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title" value="<?= $article['title'] ?? '' ?>">
                        <?php if ($errors['title']) : ?>
                            <p class="text-danger"><?= $errors['title'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="image">Image <p style="font-size: 10px;">( merci de coller l'URL d'une image )</p></label>
                        <input type="text" name="image" id="image" value="<?= $article['image'] ?? '' ?>">
                        <?php if ($errors['image']) : ?>
                            <p class="text-danger"><?= $errors['image'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="category">Catégorie</label>
                        <select name="category" id="category">
                            <option <?= !isset($article['category']) || $article['category'] === 'Technologie' ? 'selected' : '' ?> value="Technologie">Technologie</option>
                            <option <?= isset($article['category']) && $article['category'] === 'Travail' ? 'selected' : '' ?> value="Travail">Travail</option>
                            <option <?= isset($article['category']) && $article['category'] === 'Société' ? 'selected' : '' ?> value="Société">Société</option>
                            <option <?= isset($article['category']) && $article['category'] === 'Evénements' ? 'selected' : '' ?> value="Evénements">Evénements</option>
                            <option <?= isset($article['category']) && $article['category'] === 'Nature' ? 'selected' : '' ?> value="Nature">Nature</option>
                            <option <?= isset($article['category']) && $article['category'] === 'Sport' ? 'selected' : '' ?> value="Sport">Sport</option>
                            <option <?= isset($article['category']) && $article['category'] === 'Musique' ? 'selected' : '' ?> value="Musique">Musique</option>
                            <option <?= isset($article['category']) && $article['category'] === 'Divers' ? 'selected' : '' ?> value="Divers">Divers</option>
                        </select>
                        <?php if ($errors['category']) : ?>
                            <p class="text-danger"><?= $errors['category'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="content">Content</label>
                        <textarea name="content" id="content"><?= $article['content'] ?? '' ?></textarea>
                        <?php if ($errors['content']) : ?>
                            <p class="text-danger"><?= $errors['content'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="/blog/form-actions">
                        <a href="/blog/" class="btn btn-secondary" type="button">Annuler</a>
                        <button class="btn btn-primary" type="submit"><?= $id ? 'Modifier' : 'Sauvegarder' ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>