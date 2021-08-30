<?php
$title = "Affiche tous les articles";
include 'header.php';

foreach ($articles as $article) : ?>
    <article>
        <h1><?= trim(filter_var($article->getTitle(), FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?></h1>
        <p><?= nl2br(trim(filter_var($article->getDescription(), FILTER_SANITIZE_FULL_SPECIAL_CHARS))) ?></p>
        <a href="article<?= DIRECTORY_SEPARATOR . $article->getId_article() . DIRECTORY_SEPARATOR ?>show"><button id="btn-add">Lire l'article</button></a>
        <a href="article<?= DIRECTORY_SEPARATOR . $article->getId_article() . DIRECTORY_SEPARATOR ?>edit"><button id="btn-add">edit l'article</button></a>
        <a href="article<?= DIRECTORY_SEPARATOR . $article->getId_article() . DIRECTORY_SEPARATOR ?>delete"><button id="btn-add">delete l'article</button></a>
    </article>

<?php endforeach;
include 'footer.php'; ?>