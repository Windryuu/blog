<?php

use entity\Commentaire;
use repository\CommentaireRepository;

require "../../vendor/autoload.php";

session_start();

$commentaire_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$article_id = filter_input(INPUT_GET, "article", FILTER_VALIDATE_INT);

if ($commentaire_id !== false && $article_id !== false) {
    $commentaire = (new Commentaire())
        ->setIdCommentaire($commentaire_id)
        ->setIdArticle($article_id);

    if (empty($_POST)) {
        try {
            $commentaire = (new CommentaireRepository())->getCommentaireById($commentaire_id);
            include "../View/edit_commentaire.php";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        $commentaire_post["contenu"] = filter_input(INPUT_POST, "contenu");

        if (isset($commentaire_post["contenu"]) && empty(trim($commentaire_post["contenu"]))) {
            $error_messages[] = "Commentaire inexistante";
        }

        if (!isset($commentaire_post["contenu"]) || !empty($error_messages)) {
            include "../View/edit_commentaire.php";
        } else {
            $commentaire->setContenu($commentaire_post["contenu"]);

            try {
                (new CommentaireRepository())->updateCommentaire($commentaire);
                header(sprintf("location: display_one_article_controller.php?id=%d", $commentaire->getIdArticle()));
                exit;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
} else {
    header("Location: display_articles_controller.php");
    exit;
}
