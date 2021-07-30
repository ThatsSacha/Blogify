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
        </main>
    </body>
</html>