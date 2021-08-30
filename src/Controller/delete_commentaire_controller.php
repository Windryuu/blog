<?php

use repository\CommentaireRepository;

require "../../vendor/autoload.php";

session_start();

$article_id = filter_input(INPUT_GET, "article", FILTER_VALIDATE_INT);
$commentaire_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($article_id !== false && $commentaire_id !== false) {
    try {
        (new CommentaireRepository())->deleteCommentaire($commentaire_id);
        header(sprintf("location: display_one_article_controller.php?id=%d", $article_id));
        exit;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} elseif ($article_id !== false && $commentaire_id === false)  {
    header(sprintf("location: display_one_article_controller.php?id=%d", $article_id));
    exit;
} else {
    header("location:display_articles_controller.php");
    exit;
}
