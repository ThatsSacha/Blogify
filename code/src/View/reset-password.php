<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Créez votre mot de passe</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include 'assets/inc/header-tags.php'; ?>
    </head>
    <body>
        <?php include 'assets/inc/side-bar.php'; ?>
        <main class="center">
            <?php
                if ($this->json['isError']) {
                    ?>
                        <div class="card">
                            <h1>Aïe, quelque chose ne va pas...🔧</h1>
                            <h2>Ce lien n'est malheureusement pas valide.</h2>
                            <span>Veuillez refaire une demande de mot de passe et cliquez sur le lien afin de créer votre mot de passe.</span>
                        </div>
                    <?php
                } else {
                    ?>
                    <form class="reset-password">
                        <h1>Créez votre mot de passe</h1>
                        <input type="password" class="password" placeholder="Votre nouveau mot de passe">
                        <input type="password" class="password-confirm" placeholder="Confirmez votre nouveau mot de passe">
                        <div class="form-space-between">
                            <a href="login">Retour à la connexion</a>
                        </div>
                        <div class="notification"></div>
                        <button class="red-btn">
                            <div class="spinner-border small text-light" role="status"></div>
                            <i class="fas fa-unlock-alt is-active"></i>
                            Créer mon mot de passe
                        </button>
                    </form>
                    <?php
                }
            ?>
        </main>
    </body>
</html>