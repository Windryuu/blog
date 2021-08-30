<?php

namespace repository;

use entity\Article;
use PDO;

class ArticleRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $conf = parse_ini_file(MYSQL_FILE_PATH,false,INI_SCANNER_TYPED);
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $this->pdo = new PDO(
            $conf["dsn"],
            $conf["user"],
            $conf["password"],
            $options
        );
    }

    function addArticle(Article $article): int
    {
        $req = $this->pdo->prepare("INSERT INTO article (title, description)
                                        VALUES (:title, :description)");
        $req->execute([
            ":title" => $article->getTitle(),
            ":description" => $article->getDescription()
        ]);
        return $this->pdo->lastInsertId();
    }

    function getAllArticle(): array
    {
        $req = $this->pdo->prepare("SELECT * FROM article");
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $article) {
            $result[$key] = (new Article())
                ->setId_article($article["id_article"])
                ->setTitle($article["title"])
                ->setDescription($article["description"])
                ->setId_user($article["id_user"])
                ->setDate_creation($article["date_creation"]);
        }

        return $result;
    }

    function getArticleById(int $id): ?Article
    {
        $req = $this->pdo->prepare("SELECT * FROM article WHERE id_article = :id_article");
        $req->execute([":id_article" => $id]);
        $result = $req->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            return (new Article())
            ->setId_article($result["id_article"])
            ->setTitle($result["title"])
            ->setDescription($result["description"])
            ->setId_user($result["id_user"])
            ->setDate_creation($result["date_creation"]);
        } else {
            return null;
        }
    }

    function updateArticle(Article $article): void
    {
        $req = $this->pdo->prepare("UPDATE article
                        SET title = :title, description = :description
                        WHERE id_article = :id");
        $req->execute([
            ":title" => $article->getTitle(),
            ":description" => $article->getDescription(),
            ":id" => $article->getId_article()
        ]);
    }

    function deleteArticle(int $id): void
    {
        $req = $this->pdo->prepare("DELETE FROM article WHERE id_article = :id");
        $req->execute([":id" => $id]);
    }
}
