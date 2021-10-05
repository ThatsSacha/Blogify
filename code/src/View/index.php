<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | Accueil</title>
        <meta name="description" content="Blogify is a personal project realized for my school fifth project.">
        <?php include('assets/inc/header-tags.php') ?>
    </head>
    <body>
        <?php include('assets/inc/side-bar.php'); ?>
        <main class="home">
            <section class="top-section">
                <div class="card">
                    <img src="../../assets/img/memoji.png" alt="Memoji Sacha COHEN">
                </div>
                <h1>Sacha COHEN</h1>
                <h2>Développeur Web Full-Stack <small><em>Angular / Symfony</em></small></h2>
                <div class="social-networks-container">
                    <div>
                        <a href="https://github.com/ThatsSacha" target="_blank">
                            <i class="fab fa-github is-active"></i>
                        </a>
                    </div>
                    <div>
                        <a href="https://www.linkedin.com/in/sacha-cohen-1796a8154/" target="_blank">
                            <i class="fab fa-linkedin is-active"></i>
                        </a>
                    </div>
                </div>
                <a href="#last-articles" class="btn-container">
                    <button class="red-btn" href="#last-articles">
                        <i class="fas fa-book-reader is-active"></i>
                        Lire les derniers articles
                    </button>
                </a>
                <i class="fas fa-arrow-down is-active arrow-bottom"></i>
            </section>
            <section class="last-articles" id="last-articles">
                <h2>Les derniers articles</h2>
                <div class="articles-container">
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
                                            ?> ⸱ <?= $article['author']['firstName'] . ' ' . $article['author']['lastName'] ?>
                                        </span>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                </div>
                <a href="blog" class="btn">
                    <button class="green-btn">
                        <i class="far fa-newspaper is-active"></i>
                        Voir tous les articles
                    </button>
                </a>
            </section>
        </main>
    </body>
</html>