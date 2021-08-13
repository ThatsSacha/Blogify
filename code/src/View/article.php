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
            <button class="green-btn add-comment">
                <i class="bi bi-chat-left-text is-active"></i>
                Ajouter un commentaire
            </button>
            <form action="">
                <textarea placeholder="Votre commentaire..."></textarea>
                <div class="notification"></div>
                <button class="red-btn">
                    <i class="bi bi-arrow-right is-active"></i>
                    Commenter cet article
                </button>
            </form>
        </main>
    </body>
</html>