<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatrimoineMobilierRepository")
 */
class PatrimoineMobilier extends Patrimoine
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventaireMobilier", mappedBy="patrimoineMobilier", cascade={"persist"})
     */
    protected $inventaires;

    public function __construct()
    {
        $this->inventaires = new ArrayCollection();
    }

    /**
     * @return Collection|InventaireMobilier[]
     */
    public function getInventaires(): Collection
    {
        return $this->inventaires;
    }

    public function addInventaire(InventaireMobilier $inventaire): self
    {
        if (!$this->inventaires->contains($inventaire)) {
            $this->inventaires[] = $inventaire;
            $inventaire->setPatrimoineMobilier($this);
        }

        return $this;
    }

    public function removeInventaire(InventaireMobilier $inventaire): self
    {
        if ($this->inventaires->contains($inventaire)) {
            $this->inventaires->removeElement($inventaire);
            // set the owning side to null (unless already changed)
            if ($inventaire->getPatrimoineMobilier() === $this) {
                $inventaire->setPatrimoineMobilier(null);
            }
        }

        return $this;
    }
}
