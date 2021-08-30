<?php

namespace entity;

class Genre
{
    private int $id_genre;
    private string $type;

    public function __construct()
    {
    }

    /**
     * Get the value of id_genre
     */ 
    public function getId_genre()
    {
        return $this->id_genre;
    }

    /**
     * Set the value of id_genre
     *
     * @return  self
     */ 
    public function setId_genre($id_genre)
    {
        $this->id_genre = $id_genre;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
