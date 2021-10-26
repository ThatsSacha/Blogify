<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Ajouter un article</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include 'assets/inc/header-tags.php'; ?>
    </head>
    <body>
        <?php include 'assets/inc/side-bar.php'; ?>
        <main class="blog">
            <h1>Ajouter un article 🖋</h1>
            <form class="w-100 add-article">
                <input type="text" class="title" placeholder="Titre de l'article">
                <input type="text" class="teaser" placeholder="Phrase d'accroche">
                <label for="cover">Photo de l'article</label>
                <input type="file" class="cover">
                <textarea class="content" placeholder="Corps de l'article"></textarea>
                <input type="text" class="cover-credit" placeholder="Crédit photo">
                <div class="notification"></div>
                <button class="green-btn">
                    <div class="spinner-border small text-light" role="status"></div>
                    <i class="bi bi-plus-circle is-active"></i>
                    Ajouter
                </button>
            </form>
        </main>
    </body>
</html>