<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Mon profil</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include 'assets/inc/header-tags.php'; ?>
    </head>
    <body>
        <?php include 'assets/inc/side-bar.php'; ?>
        <main>
            <h1>Salut <?= $this->json['firstName'] ?> 👋🏼</h1>
            <form class="update-profile w-100">
                <input type="text" class="first-name" placeholder="Votre prénom" value="<?= $this->json['firstName'] ?>">
                <input type="text" class="last-name" placeholder="Votre nom de famille" value="<?= $this->json['lastName'] ?>">
                <input type="text" class="pseudo" placeholder="Votre pseudo" value="<?= $this->json['pseudo'] ?>">
                <input type="text" class="mail" placeholder="Votre adresse mail" value="<?= $this->json['mail'] ?>">
                <div class="notification"></div>
                <button class="green-btn">
                    <div class="spinner-border small text-light" role="status"></div>
                    <i class="bi bi-save is-active"></i>
                    Mettre à jour
                </button>
            </form>
            <section class="informations">
                <h2>Mes informations 📌</h2>
                <div class="card">
                    <h3>Rôle(s)</h3>
                    <span><?= implode(',', $this->json['roles']); ?></span>
                </div>
                <div class="card">
                    <h3>Inscription</h3>
                    <span>
                        Le 
                        <?=
                            strftime('%A %d %B %Y à %H:%M', strtotime($this->json['registeredAt']['date'])); 
                        ?>
                    </span>
                </div>
            </section>
            <section class="users-not-validated">
                <h3>Utilisateurs à valider 🙆🏻‍♂️</h3>
            </section>
        </main>
    </body>
</html>