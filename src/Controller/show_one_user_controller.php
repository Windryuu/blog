<?php

use repository\UserRepository;

require "../../vendor/autoload.php";

session_start();

$user_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($user_id !== false) {
    try {
        $userDao = new UserRepository();
        $user = $userDao->getUserById($user_id);

        if (!is_null($user)) {
            require "../View/show_one_user.php";
        } else {
            header("Location: display_articles_controller.php");
            exit;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    header("Location: display_articles_controller.php");
    exit;
}