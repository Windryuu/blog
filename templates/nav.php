<a href="../../../../article"><button id="btn-add">Accueil</button></a>
<?php if (!isset($_SESSION["user"])) : ?>
<a href="../../../../signup"><button id="btn-add">S'enregistrer</button></a>
<a href="../../../../signin"><button id="btn-add">Se connecter</button></a>
<?php else : ?>
<a href="article/new"><button id="btn-add">Ajouter un article</button></a>
<a href="../../../../userlist"><button id="btn-add">Liste des utilisateurs</button></a>
<a href="show_one_user_controller.php?id=<?= unserialize($_SESSION["user"])->getId_user(); ?>"><button id="btn-add">Afficher mon profil</button></a>
<a href="../../../../signout"><button id="btn-add">Se déconnecter</button></a>
<?php endif; ?>