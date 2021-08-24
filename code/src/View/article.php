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
            <?php
                if ($this->authService->isAdmin()) {
                    ?>
                        <div class="btn-group">
                            <a href="update-article?id=<?= $_GET['id'] ?>">
                                <button class="green-btn small-btn">
                                    <i class="bi bi-pencil is-active"></i>
                                    Mettre à jour l'article
                                </button>
                            </a>
                            <button class="red-btn small-btn delete-article" data-article="<?= $_GET['id'] ?>">
                                <i class="bi bi-trash is-active"></i>
                                Supprimer l'article
                            </button>
                        </div>
                    <?php
                }
            ?>
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
                
                <?= html_entity_decode($this->json['content']); ?>
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
            <div class="comment-list">
                <?php
                    foreach($this->json['comments'] as $comment) {
                        if ($comment['isActive'] || $this->authService->isAdmin()) {
                            ?>
                                <div class="comment card">
                                    <div class="top">
                                        <h6>Par <?= $comment['user']['firstName'] ?> <?= $comment['user']['lastName'] ?></h6>
                                        <small>
                                            <?= $comment['createdAtFrench'] ?>
                                        </small>
                                        <?php
                                            if (!$comment['isActive'] && $this->authService->isAdmin()) {
                                                ?>
                                                   <button data-article="<?= $_GET['id'] ?>" data-comment="<?= $comment['id'] ?>" class="green-btn validate-btn">
                                                        <i class="bi bi-check-circle is-active"></i>
                                                        Valider ce commentaire
                                                   </button>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <span><?= $comment['comment'] ?></span>
                                </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </main>
    </body>
</html>