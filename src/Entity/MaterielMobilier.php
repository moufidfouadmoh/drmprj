<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterielMobilierRepository")
 * @UniqueEntity(fields={"equipement","modele"}, message="materiel.unique")
 */
class MaterielMobilier extends Materiel
{
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $modele;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventaireMobilier", mappedBy="materielMobilier")
     */
    private $inventaireMobiliers;

    public function __construct()
    {
        parent::__construct();
        $this->quantite = 0;
        $this->inventaireMobiliers = new ArrayCollection();
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * @return Collection|InventaireMobilier[]
     */
    public function getInventaireMobiliers(): Collection
    {
        return $this->inventaireMobiliers;
    }

    public function addInventaireMobilier(InventaireMobilier $inventaireMobilier): self
    {
        if (!$this->inventaireMobiliers->contains($inventaireMobilier)) {
            $this->inventaireMobiliers[] = $inventaireMobilier;
            $inventaireMobilier->setMaterielMobilier($this);
        }

        return $this;
    }

    public function removeInventaireMobilier(InventaireMobilier $inventaireMobilier): self
    {
        if ($this->inventaireMobiliers->contains($inventaireMobilier)) {
            $this->inventaireMobiliers->removeElement($inventaireMobilier);
            // set the owning side to null (unless already changed)
            if ($inventaireMobilier->getMaterielMobilier() === $this) {
                $inventaireMobilier->setMaterielMobilier(null);
            }
        }

        return $this;
    }
}
