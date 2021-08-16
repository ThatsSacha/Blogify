<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | <?= $this->json['title'] ?></title>
        <meta name="description" content="<?= $this->json['teaser'] ?>">
        <?php include('assets/inc/header-tags.php') ?>
    </head>
    <body>
        <?php include('assets/inc/side-bar.php'); ?>
        <main class="article">
            <h1><?= $this->json['title'] ?></h1>
            <h2><?= $this->json['teaser'] ?></h2>
            <span class="small-text">
                Le <?=
                    strftime('%d %B %Y à %H:%M', strtotime($this->json['createdAt']['date']));
                ?>
            </span>
            <div class="cover" style="background-image: url(../../assets/img/blog/<?= $this->json['cover'] ?>)"></div>
            <span class="small-text">
                © <?= $this->json['coverCredit'] ?>
            </span>
            <p>
                <?= $this->json['content'] ?>
            </p>
            <?php
                if (count($_SESSION) > 0 && $_SESSION['logged']) {
                    ?>
                        <button class="green-btn add-comment">
                            <i class="bi bi-chat-left-text is-active"></i>
                            Ajouter un commentaire
                        </button>
                        <form action="">
                            <textarea placeholder="Votre commentaire..."></textarea>
                            <div class="notification"></div>
                            <button class="red-btn">
                                <i class="bi bi-arrow-right is-active"></i>
                                <div class="spinner-border"></div>
                                Commenter cet article
                            </button>
                        </form>
                    <?php
                } else {
                    ?>
                        <div class="not-connected">
                            <i class="bi bi-info-circle is-active"></i>
                            <span>Vous devez être connecté(e) pour commenter !</span>
                        </div>
                    <?php
                }
            ?>
        </main>
    </body>
</html>