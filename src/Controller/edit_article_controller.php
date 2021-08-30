<?php

use repository\ArticleRepository;
use entity\Article;

include "../../vendor/autoload.php";

session_start();

$article_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($article_id !== false) {

    if (empty($_POST)) {
        try {
            $article = (new ArticleRepository())->getArticleById($article_id);

            if (!is_null($article)) {
                include "../View/edit_article.php";
            } else {
                header("location:display_articles_controller.php");
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        $args = [
            "title" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => [
                    "regexp" => "#^[A-Z]#u"
                ]
            ],
            "description" => []
        ];

        $article_post = filter_input_array(
            INPUT_POST,
            $args
        );

        if (isset($article_post["title"]) && isset($article_post["description"])) {
            if ($article_post["title"] === false) {
                $error_messages[] = "Titre inexistant";
            }

            if (empty(trim($article_post["description"]))) {
                $error_messages[] = "Description inexistante";
            }
        }

        if (!(isset($article_post["title"]) && isset($article_post["description"])) || !empty($error_messages)) {
            include "../View/edit_article.php";
        } else {
            $article = (new Article())
                ->setId_article($article_id)
                ->setTitle($article_post["title"])
                ->setDescription($article_post["description"]);

            try {
                (new ArticleRepository())->updateArticle($article);
                header(sprintf("location:display_one_article_controller.php?id=%d", $article->getId_article()));
                exit;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
} else {
    header("location:display_articles_controller.php");
    exit;
}
