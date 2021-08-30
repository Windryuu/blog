<?php
$title = "Edition d'un commentaire";
include 'header.php';

if (!empty($error_messages)) : ?>
    <div>
        <ul>
            <?php foreach ($error_messages as $msg) : ?>
                <li><?= $msg ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; 

//dump($commentaires);
//dump($_POST);?>

<form action="" method="post">
    <textarea name="contenu" id="contenu" cols="30" rows="10"><?= $commentaire->getContenu() ?></textarea>
    <input type="submit" value="Envoyer">
</form>

<?php include 'footer.php'; ?>