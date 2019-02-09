<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service extends Bureau
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departement", inversedBy="services")
     */
    private $departement;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Direction", inversedBy="services")
     */
    private $directions;

    public function __construct()
    {
        parent::__construct();
        $this->directions = new ArrayCollection();
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection|Direction[]
     */
    public function getDirections(): Collection
    {
        return $this->directions;
    }

    public function addDirection(Direction $direction): self
    {
        if (!$this->directions->contains($direction)) {
            $this->directions[] = $direction;
        }

        return $this;
    }

    public function removeDirection(Direction $direction): self
    {
        if ($this->directions->contains($direction)) {
            $this->directions->removeElement($direction);
        }

        return $this;
    }
}
