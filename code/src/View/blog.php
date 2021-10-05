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
                        <div class="btn-group">
                            <a href="add-article" class="btn">
                                <button class="green-btn small-btn">
                                    <i class="bi bi-pencil is-active"></i>
                                    Ajouter un article
                                </button>
                            </a>
                        </div>
                    <?php
                }
            ?>
            <div class="articles-container">
                <?php
                    foreach($this->json as $article) {
                        ?>
                            <div class="card">
                                <a href="article?id=<?= $article['id'] ?>"></a>
                                <div class="cover" style="background-image: url(../../assets/img/blog/<?= $article['cover'] ?>)"></div>
                                <div class="bottom">
                                    <h3><?= $article['title'] ?></h3>
                                    <p><?= $article['teaser'] ?></p>
                                    <span>Le 
                                        <?= strftime('%d %B', strtotime($article['createdAt']['date'])); 
                                        ?> ⸱ <?= $article['author']['firstName'] . ' ' . $article['author']['lastName'] ?>
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