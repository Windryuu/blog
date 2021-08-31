<?php

namespace controller;

use entity\User;
use framework\Request;
use framework\Router;
use framework\Session;
use PDOException;
use repository\UserRepository;
use repository\GenreRepository;
use repository\GroupeRepository;

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

    public function userShowOne($user_id){
        if ($user_id !== false) {
            try {
                $userDao = new UserRepository();
                $user = $userDao->getUserById($user_id);
        
                if (!is_null($user)) {
                    require TEMPLATES . DIRECTORY_SEPARATOR . "show_one_user.php";
                } else {
                    header("Location:" . HTTP . "display_articles_controller.php");
                    exit;
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            header("Location:" . HTTP . "display_articles_controller.php");
            exit;
        }
    }

    public function userUpdate($user_id){
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
                    require TEMPLATES . DIRECTORY_SEPARATOR . "edit_user.php";
                } else {
                    header("Location:" . ROOT . "display_articles_controller.php");
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
                        header("Location:show");
                        exit;
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                } else {
                    try {
                        $userDao = new UserRepository();
                        $user = $userDao->getUserById($user_id);
                        $genres = (new GenreRepository())->getAllGenre();
                        $groupes = (new GroupeRepository())->getAllGroupe();
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                    require TEMPLATES . DIRECTORY_SEPARATOR . "edit_user.php";
                }
            }
        } else {
            header("Location:" . HTTP . "article");
            exit;
        }
    }
};