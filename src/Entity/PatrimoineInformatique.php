<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatrimoineInformatiqueRepository")
 */
class PatrimoineInformatique extends Patrimoine
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventaireInformatique", mappedBy="patrimoineInformatique", cascade={"persist"})
     */
    protected $inventaires;

    public function __construct()
    {
        $this->inventaires = new ArrayCollection();
    }

    /**
     * @return Collection|InventaireInformatique[]
     */
    public function getInventaires(): Collection
    {
        return $this->inventaires;
    }

    public function addInventaire(InventaireInformatique $inventaire): self
    {
        if (!$this->inventaires->contains($inventaire)) {
            $this->inventaires[] = $inventaire;
            $inventaire->setPatrimoineInformatique($this);
        }

        return $this;
    }

    public function removeInventaire(InventaireInformatique $inventaire): self
    {
        if ($this->inventaires->contains($inventaire)) {
            $this->inventaires->removeElement($inventaire);
            // set the owning side to null (unless already changed)
            if ($inventaire->getPatrimoineInformatique() === $this) {
                $inventaire->setPatrimoineInformatique(null);
            }
        }

        return $this;
    }
}
