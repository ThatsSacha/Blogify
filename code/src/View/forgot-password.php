<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Mot de passe oublié</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include('assets/inc/header-tags.php') ?>
    </head>
    <body>
        <?php include('assets/inc/side-bar.php'); ?>
        <main class="center">
            <form class="forgot-password">
                <h1>Mot de passe oublié</h1>
                <input type="text" class="mail" placeholder="Votre adresse mail">
                <div class="form-space-between">
                    <a href="login">Se connecter</a>
                    <a href="register">S'inscrire</a>
                </div>
                <div class="notification"></div>
                <button class="red-btn">
                    <div class="spinner-border small text-light" role="status"></div>
                    <i class="fas fa-unlock-alt is-active"></i>
                    Rénitialiser mon mot de passe
                </button>
            </form>
        </main>
    </body>
</html>