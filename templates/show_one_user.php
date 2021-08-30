<?php
$title = (unserialize($_SESSION["user"])->getId_user() === $user->getId_user()) ? "Mon profil" : sprintf("Le profil de %s", $user->getPseudo());
include 'header.php';
?>

<div><span>Nom : </span><span><?= $user->getNom() ?? 'N/A'; ?></span></div>
<div><span>Prénom : </span><span><?= $user->getPrenom() ?? 'N/A'; ?></span></div>
<div><span>Pseudo : </span><span><?= $user->getPseudo() ?></span></div>
<div><span>Email : </span><span><?= $user->getEmail() ?></span></div>
<div><span>Date de création : </span><span><?= $user->getDate_creation() ?></span></div>
<div><span>Genre : </span><span><?= $user->getGenre() ?? 'N/A'; ?></span></div>
<div><span>Groupe : </span><span><?= $user->getGroup() ?? 'N/A'; ?></span></div>

<a href="edit_user_controller.php?id=<?= $user->getId_user() ?>">Modifier le profil</a>

<?php include 'footer.php'; ?>