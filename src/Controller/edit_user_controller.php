<?php

use repository\GenreRepository;
use repository\GroupeRepository;
use repository\UserRepository;
use entity\User;

require "../../vendor/autoload.php";

session_start();

$user_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($user_id !== false) {
    try {
        $userDao = new UserRepository();
        $user = $userDao->getUserById($user_id);
        $genres = (new GenreRepository())->getAllGenre();
        $groupes = (new GroupeRepository())->getAllGroupe();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    if (empty($_POST)) {
        if (!is_null($user)) {
            require "../View/edit_user.php";
        } else {
            header("Location: display_articles_controller.php");
            exit;
        }
    } else {
        $args = [
            "nom" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => [
                    "regexp" => "#^[A-Z]#"
                ]
            ],
            "prenom" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => [
                    "regexp" => "#^[A-Z]#"
                ]
            ],
            "pseudo" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => [
                    "regexp" => "#^[\w\s-]+$#u"
                ]
            ],
            "email" => [
                "filter" => FILTER_VALIDATE_EMAIL
            ],
            "pwd" => [],
            "genre" => [
                "filter" => FILTER_VALIDATE_INT
            ],
            "groupe" => [
                "filter" => FILTER_VALIDATE_INT
            ]
        ];

        $edit_post = filter_input_array(INPUT_POST, $args);

        if (empty($_POST["nom"])) $edit_post["nom"] = null;
        if (empty($_POST["prenom"])) $edit_post["prenom"] = null;
        if (empty($_POST["genre"])) $edit_post["genre"] = null;
        if (empty($_POST["groupe"])) $edit_post["groupe"] = null;

        if ($edit_post["nom"] === false) {
            $error_messages[] = "Nom inexistant";
        }

        if ($edit_post["prenom"] === false) {
            $error_messages[] = "Prénom inexistant";
        }

        if ($edit_post["pseudo"] === false) {
            $error_messages[] = "Pseudo inexistant";
        }

        if ($edit_post["email"] === false) {
            $error_messages[] = "Email inexistant";
        }

        if (empty(trim($edit_post["pwd"]))) {
            $error_messages[] = "Mot de passe inexistant";
        }

        if ($edit_post["genre"] === false) {
            $error_messages[] = "Genre inexistant";
        }

        if ($edit_post["groupe"] === false) {
            $error_messages[] = "Groupe inexistant";
        }

        if (empty($error_messages)) {
            foreach ($genres as $genre) {
                if ($genre->getId_genre() === $edit_post["genre"]) $edit_post["genre"] = $genre->getType();
            }
            foreach ($groupes as $groupe) {
                if ($groupe->getId_group() === $edit_post["groupe"]) $edit_post["groupe"] = $groupe->getNom();
            }

            $edit_user = (new User)
                ->setId_user($user_id)
                ->setNom($edit_post["nom"])
                ->setPrenom($edit_post["prenom"])
                ->setPseudo($edit_post["pseudo"])
                ->setEmail($edit_post["email"])
                ->setPwd(password_hash($edit_post["pwd"], PASSWORD_DEFAULT))
                ->setGenre($edit_post["genre"])
                ->setGroup($edit_post["groupe"]);

            try {
                $userDao->updateUser($edit_user);
                header(sprintf("Location: show_one_user_controller.php?id=%d", $user_id));
                exit;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            require "../View/edit_user.php";
        }
    }
} else {
    header("Location: display_articles_controller.php");
    exit;
}
