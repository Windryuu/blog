<?php

namespace entity;

class Groupe
{
    private int $id_group;
    private string $nom;
    private ?int $id_user;

    public function __construct()
    {
    }

    /**
     * Get the value of id_group
     */ 
    public function getId_group()
    {
        return $this->id_group;
    }

    /**
     * Set the value of id_group
     *
     * @return  self
     */ 
    public function setId_group($id_group)
    {
        $this->id_group = $id_group;

        return $this;
    }

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of id_user
     */ 
    public function getId_user()
    {
        return $this->id_user;
    }

    /**
     * Set the value of id_user
     *
     * @return  self
     */ 
    public function setId_user($id_user)
    {
        $this->id_user = $id_user;

        return $this;
    }
}
