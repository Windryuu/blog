<?php

include "../../vendor/autoload.php";

use repository\UserRepository;
use entity\User;

session_start();

if (isset($_SESSION["user"])) {
    header("Location: display_articles_controller.php");
    exit;
}

if (empty($_POST)) {
    include "../View/signin.php";
} else {
    $args = [
        "email" => [
            "filter" => FILTER_VALIDATE_EMAIL
        ],
        "pwd" => []
    ];

    $signin_user = filter_input_array(INPUT_POST, $args);

    if ($signin_user["email"] === false) {
        $error_messages[] = "Email inexistant";
    }

    if (empty(trim($signin_user["pwd"]))) {
        $error_messages[] = "Mot de passe inexistant";
    }

    if (empty($error_messages)) {
        $signin_user = (new User())
            ->setEmail($signin_user["email"])
            ->setPwd($signin_user["pwd"]);

        try {
            $userDao = new UserRepository();
            $user = $userDao->getUserByEmail($signin_user->getEmail());

            if (!is_null($user)) {
                if (password_verify($signin_user->getPwd(), $user->getPwd())) {
                    $user = $userDao->getUserById($user->getId_user());
                    session_regenerate_id(true);
                    $_SESSION["user"] = serialize($user);
                    header("Location: display_articles_controller.php");
                    exit;
                } else {
                    $error_messages[] = "Mot de passe erroné";
                    include "../View/signin.php";
                }
            } else {
                $error_messages[] = "Email erroné";
                include "../View/signin.php";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        include "../View/signin.php";
    }
}
