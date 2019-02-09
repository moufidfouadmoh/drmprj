<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartementRepository")
 */
class Departement extends Bureau
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="departement")
     */
    private $services;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Direction", inversedBy="departements")
     */
    private $direction;

    public function __construct()
    {
        parent::__construct();
        $this->services = new ArrayCollection();
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
            $service->setDepartement($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getDepartement() === $this) {
                $service->setDepartement(null);
            }
        }

        return $this;
    }

    public function getDirection(): ?Direction
    {
        return $this->direction;
    }

    public function setDirection(?Direction $direction): self
    {
        $this->direction = $direction;

        return $this;
    }
}
