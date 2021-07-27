<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Mon profil</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include('assets/inc/header-tags.php') ?>
    </head>
    <body>
        <?php include('assets/inc/side-bar.php'); ?>
        <main class="center">
            <?php print_r($this->result); ?>
            <form class="register">
                <h1>Inscription</h1>
                <input type="text" class="first-name" placeholder="Votre prénom">
                <input type="text" class="last-name" placeholder="Votre nom de famille">
                <input type="text" class="pseudo" placeholder="Votre pseudo">
                <input type="text" class="mail" placeholder="Votre adresse mail">
                <input type="password" class="password" placeholder="Votre mot de passe">
                <input type="password" class="password-confirm" placeholder="Confirmez votre mot de passe">
                <div class="form-space-between">
                    <a href="login">Se connecter</a>
                </div>
                <div class="notification"></div>
                <button class="red-btn">
                    <div class="spinner-border small text-light" role="status"></div>
                    <i class="bi bi-person-plus is-active"></i>
                    S'inscrire
                </button>
            </form>
        </main>
    </body>
</html>