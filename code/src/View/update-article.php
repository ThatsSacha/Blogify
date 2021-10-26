<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Modifier un article</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include 'assets/inc/header-tags.php'; ?>
    </head>
    <body>
        <?php include 'assets/inc/side-bar.php'; ?>
        <main class="update-article">
            <h1>Modifier un article 🖋</h1>
            <form class="w-100 update-article">
                <input type="text" class="title" placeholder="Titre de l'article" value="<?= $this->json['title'] ?>">
                <input type="text" class="teaser" placeholder="Phrase d'accroche" value="<?= $this->json['teaser'] ?>">
                <label for="cover">Photo de l'article</label>
                <img src="../../assets/img/blog/<?= $this->json['cover'] ?>" alt="<?= $this->json['title'] ?>">
                <input type="file" class="cover">
                <textarea class="content" placeholder="Corps de l'article"><?= str_replace('<br />', '', html_entity_decode($this->json['content'])); ?></textarea>
                <input type="text" class="cover-credit" placeholder="Crédit photo" value="<?= $this->json['coverCredit'] ?>">
                <div class="notification"></div>
                <button class="green-btn">
                    <div class="spinner-border small text-light" role="status"></div>
                    <i class="bi bi-plus-circle is-active"></i>
                    Mettre à jour
                </button>
            </form>
        </main>
    </body>
</html>