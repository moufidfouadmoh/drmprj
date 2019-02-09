<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterielInformatiqueRepository")
 * @UniqueEntity(fields={"equipement","marque"}, message="materiel.informatique.unique")
 */
class MaterielInformatique extends Materiel
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marque", inversedBy="materiels", cascade={"persist"})
     */
    private $marque;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\InterventionInterne", mappedBy="materielInformatiques")
     */
    private $interventionInternes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventaireInformatique", mappedBy="materielInformatique")
     */
    private $inventaireInformatiques;


    public function __construct()
    {
        parent::__construct();
        $this->quantite = 0;
        $this->interventionInternes = new ArrayCollection();
        $this->inventaireInformatiques = new ArrayCollection();
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|InterventionInterne[]
     */
    public function getInterventionInternes(): Collection
    {
        return $this->interventionInternes;
    }

    public function addInterventionInterne(InterventionInterne $interventionInterne): self
    {
        if (!$this->interventionInternes->contains($interventionInterne)) {
            $this->interventionInternes[] = $interventionInterne;
            $interventionInterne->addMaterielInformatique($this);
        }

        return $this;
    }

    public function removeInterventionInterne(InterventionInterne $interventionInterne): self
    {
        if ($this->interventionInternes->contains($interventionInterne)) {
            $this->interventionInternes->removeElement($interventionInterne);
            $interventionInterne->removeMaterielInformatique($this);
        }

        return $this;
    }

    /**
     * @return Collection|InventaireInformatique[]
     */
    public function getInventaireInformatiques(): Collection
    {
        return $this->inventaireInformatiques;
    }

    public function addInventaireInformatique(InventaireInformatique $inventaireInformatique): self
    {
        if (!$this->inventaireInformatiques->contains($inventaireInformatique)) {
            $this->inventaireInformatiques[] = $inventaireInformatique;
            $inventaireInformatique->setMaterielInformatique($this);
        }

        return $this;
    }

    public function removeInventaireInformatique(InventaireInformatique $inventaireInformatique): self
    {
        if ($this->inventaireInformatiques->contains($inventaireInformatique)) {
            $this->inventaireInformatiques->removeElement($inventaireInformatique);
            // set the owning side to null (unless already changed)
            if ($inventaireInformatique->getMaterielInformatique() === $this) {
                $inventaireInformatique->setMaterielInformatique(null);
            }
        }

        return $this;
    }
}
