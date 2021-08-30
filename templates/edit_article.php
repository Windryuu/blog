<?php
$title = "Editer un article";
include 'header.php';

if (!empty($error_messages)) : ?>
    <div>
        <ul>
            <?php foreach ($error_messages as $msg) : ?>
                <li><?= $msg ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?= "BOOP" ?>

<form action="" method="post">
    <input type="text" name="title" value="<?= $article->getTitle() ?>">
    <textarea name="description" id="description" cols="30" rows="10"><?= $article->getDescription() ?></textarea>
    <input type="submit" value="Envoyer">
</form>

<?php include 'footer.php'; ?>