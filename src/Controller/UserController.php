<?php

namespace controller;

use entity\User;
use framework\Request;
use framework\Router;
use framework\Session;
use PDOException;
use repository\UserRepository;

class UserController{
    public function __construct(
        private Request $request,
        private Router $router,
        private Session $session
    )
    {
        
    }

    public function userSignup(){
        if (isset($_SESSION["user"])) {
            header("Location:../../../../article");
            exit;
        }
        
        if (empty($_POST)) {
            include TEMPLATES . DIRECTORY_SEPARATOR . "signup.php";
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
                        header("Location: ../../../../article");
                        exit;
                    } else {
                        $error_messages[] = "Cet email est déjà utilisé";
                        include TEMPLATES . DIRECTORY_SEPARATOR . "signup.php";
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            } else {
                include TEMPLATES . DIRECTORY_SEPARATOR . "signup.php";
            }
        }
    }

    public function userSignin() {
        if (isset($_SESSION["user"])) {
            header("Location: ../../../../article");
            exit;
        }
        
        if (empty($_POST)) {
            include TEMPLATES . DIRECTORY_SEPARATOR . "signin.php";
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
                            header("Location: ../../../../article");
                            exit;
                        } else {
                            $error_messages[] = "Mot de passe erroné";
                            include TEMPLATES . DIRECTORY_SEPARATOR . "signin.php";
                        }
                    } else {
                        $error_messages[] = "Email erroné";
                        include TEMPLATES . DIRECTORY_SEPARATOR . "signin.php";
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            } else {
                include TEMPLATES . DIRECTORY_SEPARATOR . "signin.php";
            }
        }
    }

    public function userSignout() {
        session_destroy();
        unset($_SESSION);
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            null,
            strtotime('yesterday'),
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );

        header("Location: ../../../../article");
        exit;
    }

    public function userList(){
        try {
            $userDao = new UserRepository();
            $listUsers = $userDao->getAllUser();
            include TEMPLATES . DIRECTORY_SEPARATOR . "show_users.php";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
};