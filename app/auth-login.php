<?php

require_once __DIR__ . '/database/database.php';
$AuthDB = require_once __DIR__ . '/database/security.php';

const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_INVALID_EMAIL = 'L\'email n\'est pas valide';
const ERROR_INVALID_PASSWORD = 'Le mot de passe est incorrect';
const ERROR_NO_USER = 'Ce compte n\'existe pas';

$error = [
    'email' => '',
    'password' => '',
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, [
        'email' => FILTER_SANITIZE_EMAIL,
        'password' => null
    ]);
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!$email) {
        $error['email'] = ERROR_REQUIRED;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = ERROR_INVALID_EMAIL;
    }
    if (!$password) {
        $error['password'] = ERROR_REQUIRED;
    }
    # si pas d'erreur
    if (empty(array_filter($error, fn ($e) => $e !== ''))) {

        $AuthDB->login($email, $password);
        header('location:blog/profile.php');
    }
}

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/blog/public/css/formulaire.css">
    <link rel="stylesheet" href="/blog/public/css/auth_login.css">
    <title>Connexion</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="block p-20 form-container">

                <h1>Connexion</h1>

                <form action="blog/auth-login.php" method="POST">

                    <!-- email -->
                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?= $email ?? '' ?>">
                        <?php if ($error['email']) : ?>
                            <p class="text-danger"><?= $error['email'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- password -->
                    <div class="form-control">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password">
                        <?php if ($error['password']) : ?>
                            <p class="text-danger"><?= $error['password'] ?></p>
                        <?php endif; ?>
                    </div>



                    <!-- button -->
                    <div class="form-actions">
                        <a href="/blog/" class="btn btn-secondary" type="button">Annuler</a>
                        <button class="btn btn-primary" type="submit">Connexion</button>
                    </div>

                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>