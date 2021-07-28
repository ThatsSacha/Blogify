<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Blog</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include('assets/inc/header-tags.php') ?>
    </head>
    <body>
        <?php include('assets/inc/side-bar.php'); ?>
        <main>
            <h1>Blog 📃</h1>
            <div class="article-container">
                <?php
                    foreach($this->json as $article) {
                        ?>
                            <div class="card">
                                <div class="cover" style="background-image: url(../../assets/img/blog/<?= $article['cover'] ?>)"></div>
                                <h3><?= $article['title'] ?></h3>
                                <span>Le 8 mars - Sacha COHEN</span>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </main>
    </body>
</html>