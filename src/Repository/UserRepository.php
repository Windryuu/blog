<?php

namespace repository;

use PDO;
use entity\User;

class UserRepository
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

    public function addUser(User $user): void
    {
        $req = $this->pdo->prepare("INSERT INTO user (pseudo, email, pwd) VALUES (:pseudo, :email, :pwd)");
        $req->execute([
            ":pseudo" => $user->getPseudo(),
            ":email" => $user->getEmail(),
            ":pwd" => $user->getPwd()
        ]);
    }

    public function getAllUser(): array
    {
        $req = $this->pdo->prepare("SELECT u.id_user AS id,
                                        u.nom AS nom,
                                        u.prenom AS prenom,
                                        u.pseudo AS pseudo,
                                        u.email AS email,
                                        u.date_creation AS date_creation,
                                        g.type AS genre,
                                        r.nom AS groupe
                                    FROM user AS u
                                    LEFT OUTER JOIN genre AS g
                                        ON u.id_genre = g.id_genre
                                    LEFT OUTER JOIN groupe AS r
                                        ON u.id_group = r.id_group
                                    ORDER BY id ASC
        ");
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $user) {
            $result[$key] = (new User())
                ->setId_user($user["id"])
                ->setNom($user["nom"])
                ->setPrenom($user["prenom"])
                ->setPseudo($user["pseudo"])
                ->setEmail($user["email"])
                ->setDate_creation($user["date_creation"])
                ->setGenre($user["genre"])
                ->setGroup($user["groupe"]);
        }

        return $result;
    }

    public function getUserById(int $id): ?User
    {
        $req = $this->pdo->prepare("SELECT u.id_user AS id,
                                        u.nom AS nom,
                                        u.prenom AS prenom,
                                        u.pseudo AS pseudo,
                                        u.email AS email,
                                        u.date_creation AS date_creation,
                                        g.type AS genre,
                                        r.nom AS groupe
                                    FROM user AS u
                                    LEFT OUTER JOIN genre AS g
                                        ON u.id_genre = g.id_genre
                                    LEFT OUTER JOIN groupe AS r
                                        ON u.id_group = r.id_group
                                    WHERE u.id_user = :id_user
        ");
        $req->execute([":id_user" => $id]);
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            return (new User())
                ->setId_user($result["id"])
                ->setNom($result["nom"])
                ->setPrenom($result["prenom"])
                ->setPseudo($result["pseudo"])
                ->setEmail($result["email"])
                ->setDate_creation($result["date_creation"])
                ->setGenre($result["genre"])
                ->setGroup($result["groupe"]);
        } else {
            return null;
        }
    }

    public function getUserByEmail(string $email): ?User
    {
        $req = $this->pdo->prepare("SELECT id_user, email, pwd
                                    FROM user
                                    WHERE email = :email
        ");
        $req->execute([":email" => $email]);
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            return (new User())
                ->setId_user($result["id_user"])
                ->setEmail($result["email"])
                ->setPwd($result["pwd"]);
        } else {
            return null;
        }
    }

    public function updateUser(User $user): void
    {
        $req = $this->pdo->prepare("UPDATE user
                                    SET nom = :nom,
                                        prenom = :prenom,
                                        pseudo = :pseudo,
                                        email = :email,
                                        pwd = :pwd,
                                        id_genre = (
                                            SELECT id_genre
                                            FROM genre
                                            WHERE type LIKE :genre
                                        ),
                                        id_group = (
                                            SELECT id_group
                                            FROM groupe
                                            WHERE nom LIKE :group
                                        )
                                    WHERE id_user = :id_user
        ");
        $req->execute([
            ":id_user" => $user->getId_user(),
            ":nom" => $user->getNom(),
            ":prenom" => $user->getPrenom(),
            ":pseudo" => $user->getPseudo(),
            ":email" => $user->getEmail(),
            ":pwd" => $user->getPwd(),
            ":genre" => $user->getGenre(),
            ":group" => $user->getGroup(),
        ]);
    }

    public function deleteUser(int $id): void
    {
        $req = $this->pdo->prepare("DELETE FROM user WHERE id_user = :id_user");
        $req->execute([":id_user" => $id]);
    }
}
