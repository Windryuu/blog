<?php

use repository\CommentaireRepository;
use entity\Commentaire;

require "../../vendor/autoload.php";

session_start();

$article_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($article_id !== false) {
    if (empty($_POST)) {
        include "../View/add_commentaire.php";
    } else {
        $commentaire_post = [
            "article_id" => $article_id,
            "contenu" => filter_input(INPUT_POST, "contenu")
        ];

        if (isset($commentaire_post["contenu"]) && empty(trim($commentaire_post["contenu"]))) {
            $error_messages[] = "Commentaire inexistante";
        }

        if (!isset($commentaire_post["contenu"]) || !empty($error_messages)) {
            include "../View/add_commentaire.php";
        } else {
            $commentaire = (new Commentaire())
                ->setIdArticle($commentaire_post["article_id"])
                ->setContenu($commentaire_post["contenu"]);

            try {
                (new CommentaireRepository())->addCommentaire($commentaire);
                header(sprintf("location: display_one_article_controller.php?id=%d", $article_id));
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
} else {
    header("Location: display_articles_controller.php");
}
