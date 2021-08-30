<?php

namespace repository;

use entity\Genre;
use PDO;

class GenreRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $conf = parse_ini_file(MYSQL_FILE_PATH,false,INI_SCANNER_TYPED);
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $this->pdo = new PDO(
            $conf["dsn"],
            $conf["user"],
            $conf["password"],
            $options
        );
    }

    public function getAllGenre(): array
    {
        $req = $this->pdo->prepare("SELECT * FROM genre");
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $genre) {
            $result[$key] = (new Genre)
                ->setId_genre($genre["id_genre"])
                ->setType($genre["type"]);
        }

        return $result;
    }
}
