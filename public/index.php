<?php

use framework\{Request,Session, Router};

require "../config/setup.php";

session_start();
$session = new Session(
    isset($_SESSION["user"]) ? unserialize($_SESSION["user"]) : null
);
$request = new Request(
    filter_input(INPUT_SERVER,"REQUEST_URI"),
    filter_input(INPUT_SERVER,"REQUEST_METHOD")
);

//dump($request->getUri(),$request->getMethod(), $session->getUser());

$router = new Router($request);

call_user_func_array($router->getCallback(),[$request,$router,$session,$router->getMatches()]);