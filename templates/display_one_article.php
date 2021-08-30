<?php
$title = sprintf("Affiche l'article %d", $article->getId_article());
include 'header.php';
?>
<article>
    <h1><?= trim(filter_var($article->getTitle(), FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?></h1>
    <p><?= nl2br(trim(filter_var($article->getDescription(), FILTER_SANITIZE_FULL_SPECIAL_CHARS))) ?></p>
    <div class="btn-pack-class">
        <a href="edit_article_controller.php?id=<?= $article->getId_article() ?>"><button id="btn-add">Editer l'article</button></a>
        <a href="delete_article_controller.php?id=<?= $article->getId_article() ?>"><button id="btn-add">Supprimer l'article</button></a>
        <a href="comment"><button id="btn-add">Ajouter un commentaire</button></a>
    </div>
    
</article>
<?php if (!empty($commentaires)) : ?>
    <div class="commentaire">
        <?php foreach ($commentaires as $commentaire) : ?>
            <div class="sub-commentaire">
                <span><?= $commentaire->getDateCreation() ?></span>
                <div class="btn-pack-class">
                    <a href=<?="comment" . DIRECTORY_SEPARATOR . $commentaire->getIdCommentaire() . DIRECTORY_SEPARATOR . "edit"?>><button id="btn-add">Editer le commentaire</button></a>
                    <a href=<?="comment" . DIRECTORY_SEPARATOR . $commentaire->getIdCommentaire() . DIRECTORY_SEPARATOR . "delete"?>><button id="btn-add">Supprimer le commentaire</button></a>
            
                </div>
                <p><?= $commentaire->getContenu() ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>