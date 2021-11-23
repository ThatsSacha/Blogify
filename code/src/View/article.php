<!DOCTYPE html>
<html>
    <head>
        <title>Blogify | <?= $this->json['title'] ?></title>
        <meta name="description" content="<?= $this->json['teaser'] ?>">
        <?php include 'assets/inc/header-tags.php'; ?>
    </head>
    <body>
        <?php include 'assets/inc/side-bar.php'; ?>
        <main class="article">
            <?php
                if ($this->authService->isAdmin()) {
                    ?>
                        <div class="btn-group">
                            <a class="btn" href="update-article?id=<?= htmlspecialchars_decode($_GET['id']) ?>">
                                <button class="green-btn small-btn">
                                    <i class="bi bi-pencil is-active"></i>
                                    Mettre à jour l'article
                                </button>
                            </a>
                            <button class="red-btn small-btn delete-article" data-article="<?= htmlspecialchars_decode($_GET['id']) ?>">
                                <i class="bi bi-trash is-active"></i>
                                Supprimer l'article
                            </button>
                        </div>
                    <?php
                }
            ?>
            <h1><?= htmlspecialchars_decode($this->json['title']); ?></h1>
            <h2><?= htmlspecialchars_decode($this->json['teaser']); ?></h2>
            <span class="small-text">
                Le <?=
                    strftime('%d %B %Y à %H:%M', strtotime(htmlspecialchars_decode($this->json['createdAt']['date'])));
                ?>
                , par <?= htmlspecialchars_decode($this->json['author']['firstName']); ?> <?= htmlspecialchars_decode($this->json['author']['lastName']); ?>.
            </span>
            <?php
                if (isset($this->json['updatedAt']['date']) && $this->json['updatedAt']['date'] !== null) {
                    ?>
                        <span class="small-text">Mit à jour le <?= strftime('%d %B %Y à %H:%M', strtotime(htmlspecialchars_decode($this->json['updatedAt']['date']))); ?></span>
                    <?php
                }
            ?>
            <div class="cover" style="background-image: url(../../assets/img/blog/<?= htmlspecialchars_decode($this->json['cover']) ?>)"></div>
            <span class="small-text">
                © <?= htmlspecialchars_decode($this->json['coverCredit']); ?>
            </span>
            <p>
                <?= htmlspecialchars_decode($this->json['content']); ?>
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
                                        <h6>Par <?= htmlspecialchars_decode($comment['user']['firstName']); ?> <?= htmlspecialchars_decode($comment['user']['lastName']); ?></h6>
                                        <small>
                                            <?= htmlspecialchars_decode($comment['createdAtFrench']); ?>
                                        </small>
                                        <?php
                                            if (!$comment['isActive'] && $this->authService->isAdmin()) {
                                                ?>
                                                   <button data-article="<?= htmlspecialchars_decode($_GET['id']); ?>" data-comment="<?= htmlspecialchars_decode($comment['id']); ?>" class="green-btn validate-btn">
                                                        <i class="bi bi-check-circle is-active"></i>
                                                        Valider ce commentaire
                                                   </button>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <span><?= htmlspecialchars_decode($comment['comment']); ?></span>
                                </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </main>
    </body>
</html>