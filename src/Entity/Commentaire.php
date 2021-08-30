<?php

namespace entity;

class Commentaire
{
    private int $id_commentaire;
    private int $id_article;
    private ?int $id_user;
    private string $contenu;
    private $date_creation;
    private $date_modification;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getIdCommentaire(): int
    {
        return $this->id_commentaire;
    }

    /**
     * @param int $id_commentaire
     */
    public function setIdCommentaire(int $id_commentaire): self
    {
        $this->id_commentaire = $id_commentaire;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdArticle(): int
    {
        return $this->id_article;
    }

    /**
     * @param int $id_article
     */
    public function setIdArticle(int $id_article): self
    {
        $this->id_article = $id_article;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    /**
     * @param int|null $id_user
     */
    public function setIdUser(?int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * @return string
     */
    public function getContenu(): string
    {
        return $this->contenu;
    }

    /**
     * @param string $contenu
     */
    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }

    /**
     * @param mixed $date_creation
     */
    public function setDateCreation($date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateModification()
    {
        return $this->date_modification;
    }

    /**
     * @param mixed $date_modification
     */
    public function setDateModification($date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }
}