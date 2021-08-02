<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Blog</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include('assets/inc/header-tags.php') ?>
    </head>
    <body>
        <?php include('assets/inc/side-bar.php'); ?>
        <main class="blog">
            <h1>Blog 📃</h1>
            <?php
                if (count($_SESSION) > 0 && $_SESSION['logged'] && in_array('ROLE_SUPERADMIN', $_SESSION['user']['roles'])) {
                    ?>
                        <a href="add-article" class="btn">
                            <button class="green-btn">
                                <i class="bi bi-pencil is-active"></i>
                                Ajouter un article
                            </button>
                        </a>
                    <?php
                }
            ?>
            <div class="article-container">
                <?php
                    foreach($this->json as $article) {
                        ?>
                            <div class="card">
                                <a href="article?id=<?= $article['id'] ?>"></a>
                                <div class="cover" style="background-image: url(../../assets/img/blog/<?= $article['cover'] ?>)"></div>
                                <div class="bottom">
                                    <h3><?= $article['title'] ?></h3>
                                    <span>Le 
                                        <?= strftime('%d %B', strtotime($article['createdAt']['date'])); 
                                        ?> ⸱ Sacha COHEN
                                    </span>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </main>
    </body>
</html>