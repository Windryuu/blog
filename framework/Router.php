<?php

namespace framework;

use Closure;
use controller\ArticleController;
use controller\CommentaireController;
use controller\UserController;

class Router {

    private Closure $callback;
    private array $matches = [];

    public function __construct(Request $request)
    {
        $uri = $request->getUri();

        $this -> callback = match (true) {
            1 === preg_match("#^/$#",$uri) => function(Request $request, Router $router, Session $session) {
                (new ArticleController($request,$router,$session))->index();
            },
            1 === preg_match("#^/article$#",$uri) => function(Request $request, Router $router, Session $session
            ) {
                if("GET" === $request->getMethod()) {
                    (new ArticleController($request,$router,$session))->index();
                } else {$this->redirectToRoute("error404");}
                    //(new ArticleController($request,$router,$session))->index();
                    //créer une page 404
            },
            1 === preg_match("#^/article/new$#",$uri) => function (
                Request $request,
                Router $router,
                Session $session
            ) {
                (new ArticleController($request,$router,$session))->new();
            },
            1 === preg_match("#^/article/([0-9]+)/show$#",$uri,$this->matches) => function (
                Request $request,
                Router $router,
                Session $session,
                array $matches
            ) {
                //dump($matches);
                (new ArticleController($request,$router,$session))->show($matches[1]);
                (new CommentaireController($request,$router,$session))->indexCommentaire($matches[1]);
            },

            1 === preg_match("#^/article/([0-9]+)/delete$#",$uri,$this->matches) => function (
                Request $request,
                Router $router,
                Session $session,
                array $matches
            ) {
                //dump($matches);
                (new ArticleController($request,$router,$session))->delete($matches[1]);
            },
            1 === preg_match("#^/article/([0-9]+)/edit$#",$uri,$this->matches) => function (
                Request $request,
                Router $router,
                Session $session,
                array $matches
            ) {
                //dump($matches);
                (new ArticleController($request,$router,$session))->edit($matches[1]);
            },
            1 === preg_match("#^/article/([0-9]+)/comment$#",$uri,$this->matches) => function (
                Request $request,
                Router $router,
                Session $session,
                array $matches
            ) {
                //dump($matches);
                (new CommentaireController($request,$router,$session))->newCommentaire($matches[1]);
            },
            1 === preg_match("#^/article/([0-9]+)/comment/([0-9]+)/delete$#",$uri,$this->matches) => function (
                Request $request,
                Router $router,
                Session $session,
                array $matches
            ) {
                //dump($matches);
                (new CommentaireController($request,$router,$session))->deleteCommentaire($matches[2]);
            },
            1 === preg_match("#^/article/([0-9]+)/comment/([0-9]+)/edit$#",$uri,$this->matches) => function (
                Request $request,
                Router $router,
                Session $session,
                array $matches
            ) {
                //dump($_POST);
                //dump($matches);
                (new CommentaireController($request,$router,$session))->editCommentaire($matches[2]);
            },
            1 === preg_match("#^/signup$#",$uri) => function (
                Request $request,
                Router $router,
                Session $session
            ) {
                (new UserController($request,$router,$session))->userSignup();
            },
            1 === preg_match("#^/signin$#",$uri) => function (
                Request $request,
                Router $router,
                Session $session
            ) {
                (new UserController($request,$router,$session))->userSignin();
            },
            1 === preg_match("#^/signout$#",$uri) => function (
                Request $request,
                Router $router,
                Session $session
            ) {
                (new UserController($request,$router,$session))->userSignout();
            },
            1 === preg_match("#^/userlist$#",$uri) => function (
                Request $request,
                Router $router,
                Session $session
            ) {
                (new UserController($request,$router,$session))->userList();
            },


            default => function () {

            }

        };
    }

    /**
     * Get the value of callback
     */ 
    public function getCallback():Closure
    {
        return $this->callback;
    }
    public function redirectToRoute(mixed ...$uri):void {
        if(!is_array($uri)){
            $uri = [$uri];
        }
        
        header(sprintf("Location: /%s",implode("/",$uri)));
    }

    /**
     * Get the value of matches
     */ 
    public function getMatches()
    {
        return $this->matches;
    }
}