<?php

namespace App\Entity\Includes\Search;


use Doctrine\Common\Collections\Collection;

class CConsommationSearch
{
    /** @var string|null */
    private $user;
    /** @var Collection|null */
    private $modeles;
    /** @var Collection|null */
    private $agences;
    /** @var Collection|null */
    private $bureaus;
    /** @var \DateTime|null */
    private $debut;
    /** @var \DateTime|null */
    private $fin;

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
    public function getModeles(): ?Collection
    {
        return $this->modeles;
    }

    /**
     * @param Collection|null $modeles
     */
    public function setModeles(?Collection $modeles): void
    {
        $this->modeles = $modeles;
    }

    /**
     * @return \DateTime|null
     */
    public function getDebut(): ?\DateTime
    {
        return $this->debut;
    }

    /**
     * @param \DateTime|null $debut
     */
    public function setDebut(?\DateTime $debut): void
    {
        $this->debut = $debut;
    }

    /**
     * @return \DateTime|null
     */
    public function getFin(): ?\DateTime
    {
        return $this->fin;
    }

    /**
     * @param \DateTime|null $fin
     */
    public function setFin(?\DateTime $fin): void
    {
        $this->fin = $fin;
    }

    /**
     * @return Collection|null
     */
    public function getAgences(): ?Collection
    {
        return $this->agences;
    }

    /**
     * @param Collection|null $agences
     */
    public function setAgences(?Collection $agences): void
    {
        $this->agences = $agences;
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

}