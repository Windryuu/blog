<?php

namespace controller;


use entity\Commentaire;
use framework\Request;
use framework\Router;
use framework\Session;
use PDOException;
use repository\CommentaireRepository;

class CommentaireController{
    public function __construct(
        private Request $request,
        private Router $router,
        private Session $session
    )
    {
        
    }

    public function indexCommentaire($id){
        try {
            $commentaires = (new CommentaireRepository())->getCommentaireByArticleId($id);

        } catch (PDOException $f) {
            echo $f->getMessage();
        }

    }

    public function newCommentaire($id){
        if("GET" === $this->request->getMethod()){
            require TEMPLATES . DIRECTORY_SEPARATOR . "add_commentaire.php";
        } else if ("POST" === $this->request->getMethod()){

            if (empty($_POST)) {
                include TEMPLATES . DIRECTORY_SEPARATOR . "add_commentaire.php";
            } else {
                $commentaire_post = [
                    "article_id" => $id,
                    "contenu" => filter_input(INPUT_POST, "contenu")
                ];
        
                if (isset($commentaire_post["contenu"]) && empty(trim($commentaire_post["contenu"]))) {
                    $error_messages[] = "Commentaire inexistant";
                }
        
                if (!isset($commentaire_post["contenu"]) || !empty($error_messages)) {
                    include TEMPLATES . DIRECTORY_SEPARATOR . "add_commentaire.php";
                } else {
                    $commentaire = (new Commentaire())
                        ->setIdArticle($commentaire_post["article_id"])
                        ->setContenu($commentaire_post["contenu"]);
        
                    try {
                        (new CommentaireRepository())->addCommentaire($commentaire);
                        header("location:show");
                    } catch (PDOException $f) {
                        echo $f->getMessage();
                    }
                }
            }


        }
    }

    public function editCommentaire(int $id_commentaire){
        
    if ($id_commentaire !== false) {
        $commentaire = (new Commentaire())
        ->setIdCommentaire($id_commentaire);
        //dump($id_commentaire);
        if(empty($_POST)){
            
            try {
                $commentaire = (new CommentaireRepository())
                ->getCommentaireById($id_commentaire);
                
                
                if(!is_null($commentaire)){
                    include TEMPLATES . DIRECTORY_SEPARATOR . "edit_commentaire.php";
                } else {
                    $this->router->redirectToRoute();
                    exit;
                }
            } catch (PDOException $f) {
                echo $f->getMessage();
            }
        } else {

            $commentaire_post["contenu"] = filter_input(INPUT_POST, "contenu");

            if (isset($commentaire_post["contenu"]) && empty(trim($commentaire_post["contenu"]))) {
            $error_messages[] = "Commentaire inexistante";
        }

            if (!isset($commentaire_post["contenu"]) || !empty($error_messages)) {
                try {
                    $commentaire = (new CommentaireRepository())
                    ->getCommentaireById($id_commentaire);
                    
                    
                    if(!is_null($commentaire)){
                        include TEMPLATES . DIRECTORY_SEPARATOR . "edit_commentaire.php";
                    } else {
                        $this->router->redirectToRoute();
                        exit;
                    }
                } catch (PDOException $f) {
                    echo $f->getMessage();
                }
            } else {
            //$commentaire=(new Commentaire())->setContenu($commentaire_post["contenu"]);
            $commentaire->setContenu($commentaire_post["contenu"]);
            try {
                (new CommentaireRepository())->updateCommentaire($commentaire);
                header(sprintf("location:../../show"));
                exit;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        }
    }  else {
        header("Location:../../show");
        exit;
    }} 

    public function deleteCommentaire(int $id_commentaire) {
        try {
            $commentaire = (new CommentaireRepository())->getCommentaireById($id_commentaire);

            if (!is_null($commentaire)){
                (new CommentaireRepository())->deleteCommentaire($id_commentaire);
                header("location:../../show");
            } else {
                header("location:../../show");
            }
        } catch (PDOException $f) {
            echo $f->getMessage();
        }
    }

}

