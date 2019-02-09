<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DirectionRepository")
 */
class Direction extends Bureau
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Service", mappedBy="directions")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Departement", mappedBy="direction")
     */
    private $departements;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $concern;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Direction")
     */
    private $direction;

    public function __construct()
    {
        parent::__construct();
        $this->services = new ArrayCollection();
        $this->departements = new ArrayCollection();
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->addDirection($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            $service->removeDirection($this);
        }

        return $this;
    }

    /**
     * @return Collection|Departement[]
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departement $departement): self
    {
        if (!$this->departements->contains($departement)) {
            $this->departements[] = $departement;
            $departement->setDirection($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): self
    {
        if ($this->departements->contains($departement)) {
            $this->departements->removeElement($departement);
            // set the owning side to null (unless already changed)
            if ($departement->getDirection() === $this) {
                $departement->setDirection(null);
            }
        }

        return $this;
    }

    public function getConcern(): ?string
    {
        return $this->concern;
    }

    public function setConcern(string $concern): self
    {
        $this->concern = $concern;

        return $this;
    }

    public function getDirection(): ?self
    {
        return $this->direction;
    }

    public function setDirection(?self $direction): self
    {
        $this->direction = $direction;

        return $this;
    }
}
