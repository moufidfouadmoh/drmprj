<?php

namespace App\Entity\Includes\Search;


class LieuSearch
{
    /** @var string|null */
    private $nom;
    /** @var array|null */
    private $iles;

    /**
     * @return null|string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param null|string $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return array|null
     */
    public function getIles(): ?array
    {
        return $this->iles;
    }

    /**
     * @param array|null $iles
     */
    public function setIles(?array $iles): void
    {
        $this->iles = $iles;
    }

}