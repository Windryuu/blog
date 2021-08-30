<?php
$title = "Editer un utilisateur";
include 'header.php';

if (!empty($error_messages)) :
?>
    <div>
        <ul>
            <?php foreach ($error_messages as $msg) : ?>
                <li><?= $msg ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= sprintf("edit_user_controller.php?id=%d", $user->getId_user()); ?>" method="post">
    <label for="nom">Nom : </label><input type="text" name="nom" id="nom" value="<?= $user->getNom() ?>">
    <br>
    <label for="prenom">Prénom : </label><input type="text" name="prenom" id="prenom" value="<?= $user->getPrenom() ?>">
    <br>
    <label for="pseudo">Pseudo : </label><input type="text" name="pseudo" id="pseudo" value="<?= $user->getPseudo() ?>">
    <br>
    <label for="email">Email : </label><input type="text" name="email" id="email" value="<?= $user->getEmail() ?>">
    <br>
    <label for="pwd">Mot de passe : </label><input type="password" name="pwd" id="pwd">
    <br>
    <label for="genre">Genre : </label>
    <select name="genre" id="genre">
            <option value="" <?= (($user->getGenre() ?? 'N/A') === 'N/A') ? " selected" : ""; ?>></option>
        <?php foreach ($genres as $genre) : ?>
            <option value="<?= $genre->getId_genre() ?>" <?= (($user->getGenre() ?? 'N/A') === $genre->getType()) ? " selected" : ""; ?>><?= $genre->getType() ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <label for="groupe">Groupe : </label>
    <select name="groupe" id="groupe">
            <option value="" <?= (($user->getGroup() ?? 'N/A') === 'N/A') ? " selected" : ""; ?>></option>
        <?php foreach ($groupes as $groupe) : ?>
            <option value="<?= $groupe->getId_group() ?>" <?= (($user->getGroup() ?? 'N/A') === $groupe->getNom()) ? " selected" : ""; ?>><?= $groupe->getNom() ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <input type="submit" value="Envoyer">
</form>

<?php include 'footer.php'; ?>