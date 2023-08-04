<?php
require_once __DIR__ . '/database/database.php';
$AuthDB = require_once __DIR__ . 'database/security.php';

const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_TOO_SHORT = 'Veuillez entrer au moins 3 caractères';
const ERROR_INVALID_EMAIL = 'L\'email n\'est pas valide';
const ERROR_PASSWORD_TOO_SHORT = 'Le mot de passe doit contenir au minimum 4 caractères';
const ERROR_CONFIRM_PASSWORD = 'Le mot de passe de confirmation doit être le même que le mot de passe';

$error = [
    'firstname' => '',
    'lastname' => '',
    'email' => '',
    'password' => '',
    'confirm-password' => ''
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST, [
        'firstname' => FILTER_SANITIZE_SPECIAL_CHARS,
        'lastname' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_SANITIZE_EMAIL
    ]);
    $firstname = $input['firstname'] ?? '';
    $lastname = $input['lastname'] ?? '';
    $email = $input['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    if (!$firstname) {
        $error['firstname'] = ERROR_REQUIRED;
    } elseif (mb_strlen($firstname) < 3) {
        $error['firstname'] = ERROR_TOO_SHORT;
    }

    if (!$lastname) {
        $error['lastname'] = ERROR_REQUIRED;
    } elseif (mb_strlen($lastname) < 3) {
        $error['lastname'] = ERROR_TOO_SHORT;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = ERROR_INVALID_EMAIL;
    }
    if (mb_strlen($password) < 4) {
        $error['password'] = ERROR_PASSWORD_TOO_SHORT;
    }
    if ($password !== $confirmPassword) {
        $error['confirm-password'] = ERROR_CONFIRM_PASSWORD;
    }

    # si pas d'erreur
    if (empty(array_filter($error, fn ($e) => $e !== ''))) {
        $AuthDB->register([
            'firstname' => $firstname,
            'lastname' => $firstlastnamename,
            'email' => $email,
            'password' => $password,
        ]);
    }
}

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/formulaire.css">
    <link rel="stylesheet" href="/public/css/auth_register.css">
    <title>Enregistrement</title>
</head>

<body>
    <div class="container">

        <?php require_once 'includes/header.php' ?>

        <div class="content">

            <div class="block p-20 form-container">

                <h1>Inscription</h1>

                <form action="/auth-register.php" method="POST">

                    <!-- prenom -->
                    <div class="form-control">
                        <label for="firstname">Prénom</label>
                        <input type="text" name="firstname" id="firstname" value="<?= $firstname ?? '' ?>">
                        <?php if ($error['firstname']) : ?>
                            <p class="text-danger"><?= $error['firstname'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- nom -->
                    <div class="form-control">
                        <label for="lastname">Nom</label>
                        <input type="text" name="lastname" id="lastname" value="<?= $lastname ?? '' ?>">
                        <?php if ($error['lastname']) : ?>
                            <p class="text-danger"><?= $error['lastname'] ?></p>
                        <?php endif; ?>
                    </div>

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

                    <!-- cofirmation password -->
                    <div class="form-control">
                        <label for="confirm-password">Confirmation mot de passe</label>
                        <input type="password" name="confirm-password" id="confirm-password">
                        <?php if ($error['confirm-password']) : ?>
                            <p class="text-danger"><?= $error['confirm-password'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- button -->
                    <div class="form-actions">
                        <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                        <button class="btn btn-primary" type="submit">Valider</button>
                    </div>

                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>