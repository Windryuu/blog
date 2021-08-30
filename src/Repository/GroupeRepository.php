<?php

namespace repository;

use entity\Groupe;
use PDO;

class GroupeRepository
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

    public function getAllGroupe(): array
    {
        $req = $this->pdo->prepare("SELECT * FROM groupe");
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $groupe) {
            $result[$key] = (new Groupe)
                ->setId_group($groupe["id_group"])
                ->setNom($groupe["nom"])
                ->setId_user($groupe["id_user"]);
        }

        return $result;
    }
}
