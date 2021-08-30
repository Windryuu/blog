<?php
$title = "Ajout d'un nouvel article";
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
<form action="" method="post">
    <input type="text" name="title">
    <textarea name="description" id="description" cols="30" rows="10"></textarea>
    <input type="submit" value="Envoyer">
</form>
<?php include 'footer.php'; ?>