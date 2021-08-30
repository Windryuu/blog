<?php

namespace repository;

use entity\Commentaire;
use PDO;

class CommentaireRepository
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

    public function addCommentaire(Commentaire $commentaire): void
    {
        $req = $this->pdo->prepare("INSERT INTO commentaire (contenu, id_article) VALUES (:contenu, :id_article)");
        $req->execute([
            ":id_article" => $commentaire->getIdArticle(),
            ":contenu" => $commentaire->getContenu()
        ]);
    }

    public function getCommentaireByArticleId(int $article_id): array
    {
        $req = $this->pdo->prepare("SELECT * 
                        FROM commentaire 
                        WHERE id_article = :article_id 
                        ORDER BY date_creation ASC");
        $req->execute([":article_id" => $article_id]);
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $commentaire) {
            $result[$key] = (new Commentaire())
                ->setIdCommentaire($commentaire["id_commentaire"])
                ->setIdArticle($commentaire["id_article"])
                ->setIdUser($commentaire["id_user"])
                ->setContenu($commentaire["contenu"])
                ->setDateCreation($commentaire["date_creation"])
                ->setDateModification($commentaire["date_modification"]);
        }

        return $result;
    }

    public function getCommentaireById(int $id): Commentaire
    {
        $req = $this->pdo->prepare("SELECT * FROM commentaire WHERE id_commentaire = :id");
        $req->execute([":id" => $id]);
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return (new Commentaire())
            ->setIdArticle($result["id_article"])
            ->setIdCommentaire($result["id_commentaire"])
            ->setIdUser($result["id_user"])
            ->setContenu($result["contenu"])
            ->setDateCreation($result["date_creation"])
            ->setDateModification($result["date_modification"]);
    }

    public function updateCommentaire(Commentaire $commentaire): void
    {
        $req = $this->pdo->prepare("UPDATE commentaire
                         SET contenu = :contenu
                         WHERE id_commentaire = :id_commentaire");
        $req->execute([
            ":contenu" => $commentaire->getContenu(),
            ":id_commentaire" => $commentaire->getIdCommentaire()
        ]);
    }

    public function deleteCommentaire(int $id): void
    {
        $req = $this->pdo->prepare("DELETE FROM commentaire WHERE id_commentaire = :id");
        $req->execute([":id" => $id]);
    }
}