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
    include "../View/signup.php";
} else {
    $args = [
        "pseudo" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => [
                "regexp" => "#^[\w\s-]+$#u"
            ]
        ],
        "email" => [
            "filter" => FILTER_VALIDATE_EMAIL
        ],
        "pwd" => []
    ];

    $signup_post = filter_input_array(INPUT_POST, $args);

    if ($signup_post["pseudo"] === false) {
        $error_messages[] = "Pseudo inexistant";
    }

    if ($signup_post["email"] === false) {
        $error_messages[] = "Email inexistant";
    }

    if (empty(trim($signup_post["pwd"]))) {
        $error_messages[] = "Mot de passe inexistant";
    }

    if (empty($error_messages)) {
        try {
            $userDao = new UserRepository();
            $exist_user = $userDao->getUserByEmail($signup_post["email"]);

            if (is_null($exist_user)) {
                $signup_user = (new User())
                    ->setPseudo($signup_post["pseudo"])
                    ->setEmail($signup_post["email"])
                    ->setPwd(password_hash($signup_post["pwd"], PASSWORD_DEFAULT));
                $userDao->addUser($signup_user);
                header("Location: display_articles_controller.php");
                exit;
            } else {
                $error_messages[] = "Cet email est déjà utilisé";
                include "../View/signup.php";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        include "../View/signup.php";
    }
}
