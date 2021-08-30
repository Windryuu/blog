<?php

include "../../vendor/autoload.php";

session_start();

use repository\UserRepository;

try {
    $userDao = new UserRepository();
    $listUsers = $userDao->getAllUser();
    include "../View/show_users.php";
} catch (PDOException $e) {
    echo $e->getMessage();
}
