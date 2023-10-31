<?php

?>
<header>
    <a href="/blog/" class="logo">Free Blog</a>

    <ul class="header-menu">
        <?php if ($AuthDB->islogged()) : ?>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                <a href="/blog/form-article.php">Éditer un article</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                <a href="/blog/profile.php">Profil</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                <a href="/blog/auth-logout.php">Deconnexion</a>
            </li>
        <?php else : ?>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                <a href="/blog/auth-login.php">Connexion</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                <a href="/blog/auth-register.php">Inscription</a>
            </li>
        <?php endif ?>
    </ul>
    <div class="mobile-menu">
        <div class="mobile-menu-icon">

            <img src="public\img\mobile-menu.png" alt="">

        </div>
        <ul class="mobile-menu-item">
            <?php if ($AuthDB->islogged()) : ?>
                <div class="separateur"></div>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                    <a href="/form-article.php">Éditer un article</a>
                </li>
                <div class="separateur"></div>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                    <a href="/profile.php">Profil</a>
                </li>
                <div class="separateur"></div>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                    <a href="/auth-logout.php">Deconnexion</a>

                </li>
            <?php else : ?>
                <div class="separateur"></div>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                    <a href="/auth-login.php">Connexion</a>

                </li>
                <div class="separateur"></div>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                    <a href="/auth-register.php">Inscription</a>
                </li>
            <?php endif ?>
        </ul>
        </a>
    </div>


</header>