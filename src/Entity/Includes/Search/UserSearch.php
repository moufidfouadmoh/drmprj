<?php

namespace App\Entity\Includes\Search;


use Doctrine\Common\Collections\Collection;

class UserSearch
{
    /** @var string|null */
    private $user;
    /** @var string|null */
    private $gender;
    /** @var Collection|null */
    private $statuts;
    /** @var Collection|null */
    private $formations;
    /** @var Collection|null */
    private $bureaus;
    /** @var Collection|null */
    private $fonctions;
    /** @var Collection|null */
    private $categories;

    /**
     * @return null|string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param null|string $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection|null
     */
    public function getStatuts(): ?Collection
    {
        return $this->statuts;
    }

    /**
     * @param Collection|null $statuts
     */
    public function setStatuts(?Collection $statuts): void
    {
        $this->statuts = $statuts;
    }

    /**
     * @return Collection|null
     */
    public function getFormations(): ?Collection
    {
        return $this->formations;
    }

    /**
     * @param Collection|null $formations
     */
    public function setFormations(?Collection $formations): void
    {
        $this->formations = $formations;
    }

    /**
     * @return Collection|null
     */
    public function getBureaus(): ?Collection
    {
        return $this->bureaus;
    }

    /**
     * @param Collection|null $bureaus
     */
    public function setBureaus(?Collection $bureaus): void
    {
        $this->bureaus = $bureaus;
    }

    /**
     * @return Collection|null
     */
    public function getFonctions(): ?Collection
    {
        return $this->fonctions;
    }

    /**
     * @param Collection|null $fonctions
     */
    public function setFonctions(?Collection $fonctions): void
    {
        $this->fonctions = $fonctions;
    }

    /**
     * @return Collection|null
     */
    public function getCategories(): ?Collection
    {
        return $this->categories;
    }

    /**
     * @param Collection|null $categories
     */
    public function setCategories(?Collection $categories): void
    {
        $this->categories = $categories;
    }
    /**
     * @return null|string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param null|string $gender
     */
    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }
}