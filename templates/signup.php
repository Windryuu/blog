<?php
$title = "Identifiez vous !";
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
    <label for="pseudo">Pseudo : </label><input type="text" name="pseudo" id="pseudo">
    <label for="email">Email : </label><input type="text" name="email" id="email">
    <label for="pwd">Mot de passe : </label><input type="password" name="pwd" id="pwd">
    <input type="submit" value="Envoyer">
</form>

<?php include 'footer.php'; ?>