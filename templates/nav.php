<a href="<?=HTTP?>article"><button id="btn-add">Accueil</button></a>
<?php if (!isset($_SESSION["user"])) : ?>
<a href="<?=HTTP?>signup"><button id="btn-add">S'enregistrer</button></a>
<a href="<?=HTTP?>signin"><button id="btn-add">Se connecter</button></a>
<?php else : ?>
<a href="<?=HTTP?>article/new"><button id="btn-add">Ajouter un article</button></a>
<a href="<?=HTTP?>userlist"><button id="btn-add">Liste des utilisateurs</button></a>
<a href="<?=HTTP?>user<?= DIRECTORY_SEPARATOR . unserialize($_SESSION["user"])->getId_user() . DIRECTORY_SEPARATOR ?>show"><button id="btn-add">Afficher mon profil</button></a>
<a href="<?=HTTP?>signout"><button id="btn-add">Se déconnecter</button></a>
<?php endif; ?>