<?php

namespace controller;

use entity\Article;
use framework\Request;
use framework\Router;
use framework\Session;
use PDOException;
use repository\ArticleRepository;
use repository\CommentaireRepository;

class ArticleController{
    public function __construct(
        private Request $request,
        private Router $router,
        private Session $session
    )
    {
        
    }
    //fonction pour afficher les posts existants.
    public function index(){
        try {
            $articles = (new ArticleRepository())->getAllArticle();
            include TEMPLATES . DIRECTORY_SEPARATOR . "display_articles.php";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
    }

    //fonction pour créer un nouveau post.
    public function new(){
        if("GET" === $this->request->getMethod()){
            require TEMPLATES . DIRECTORY_SEPARATOR . 'add_article.php';
        } else if ("POST" === $this->request->getMethod()) {

        
        $args = [
            "title" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => [
                    "regexp" => "#^[A-Z]#u"
                ]
            ],
            "description" => []
        ];
        
        $article_post = filter_input_array(INPUT_POST, $args);
        
        if (isset($article_post["title"]) && isset($article_post["description"])) {
            if ($article_post["title"] === false) {
                $error_messages[] = "Titre inexistant";
            }
        
            if (empty(trim($article_post["description"]))) {
                $error_messages[] = "Description inexistante";
            }
        }
        
        if (!(isset($article_post["title"]) && isset($article_post["description"])) || !empty($error_messages)) {
            include TEMPLATES . DIRECTORY_SEPARATOR . "add_article.php";
        } else {
            $article = (new Article())
                ->setTitle($article_post["title"])
                ->setDescription($article_post["description"]);
        
            try {
                $id = (new ArticleRepository())->addArticle($article);
                $this->router->redirectToRoute("article",$id,"show");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }}

    //fonction pour montrer un post par son id.
    public function show(int $id){
        
            try {
                $article = (new ArticleRepository())->getArticleById($id);
        
                if (!is_null($article)) {
                    $commentaires = (new CommentaireRepository())->getCommentaireByArticleId($id);
                    include TEMPLATES . DIRECTORY_SEPARATOR . 'display_one_article.php';
                } else {
                    $this->router->redirectToRoute();
                    
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        
    }
    //fonction pour éditer un article selon son id.
    public function edit(int $id){
        
    if (empty($_POST)) {
        try {
            $article = (new ArticleRepository())->getArticleById($id);
            if (!is_null($article)) {
                require TEMPLATES . DIRECTORY_SEPARATOR . "edit_article.php";
            } else {
                $this->router->redirectToRoute();
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        $args = [
            "title" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => [
                    "regexp" => "#^[A-Z]#u"
                ]
            ],
            "description" => []
        ];

        $article_post = filter_input_array(
            INPUT_POST,
            $args
        );

        if (isset($article_post["title"]) && isset($article_post["description"])) {
            if ($article_post["title"] === false) {
                $error_messages[] = "Titre inexistant";
            }

            if (empty(trim($article_post["description"]))) {
                $error_messages[] = "Description inexistante";
            }
        }

        if (!(isset($article_post["title"]) && isset($article_post["description"])) || !empty($error_messages)) {
            
            try {
                $article = (new ArticleRepository())->getArticleById($id);
                if (!is_null($article)) {
                    require TEMPLATES . DIRECTORY_SEPARATOR . "edit_article.php";
                } else {
                    $this->router->redirectToRoute();
                    exit;
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            
            
        } else {
            $article = (new Article())
                ->setId_article($id)
                ->setTitle($article_post["title"])
                ->setDescription($article_post["description"]);

            try {
                (new ArticleRepository())->updateArticle($article);
                header(sprintf("location:show"));
                exit;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }


}
    
    //fonction pour supprimer son article selon son id.
    public function delete(int $id){
        
        try {
            $article = (new ArticleRepository())->getArticleById($id);
    
            if (!is_null($article)) {
                (new ArticleRepository())->deleteArticle($id);
                $this->router->redirectToRoute();
                //include TEMPLATES . DIRECTORY_SEPARATOR . 'display_articles.php';
            } else {
                $this->router->redirectToRoute();
                
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }
}