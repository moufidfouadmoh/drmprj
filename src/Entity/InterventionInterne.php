<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionInterneRepository")
 */
class InterventionInterne extends Intervention
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Bureau", inversedBy="interventions")
     */
    private $bureaus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MaterielInformatique", inversedBy="interventionInternes")
     */
    private $materielInformatiques;


    public function __construct()
    {
        parent::__construct();
        $this->bureaus = new ArrayCollection();
        $this->materielInformatiques = new ArrayCollection();
    }

    /**
     * @return Collection|Bureau[]
     */
    public function getBureaus(): Collection
    {
        return $this->bureaus;
    }

    public function addBureau(Bureau $bureau): self
    {
        if (!$this->bureaus->contains($bureau)) {
            $this->bureaus[] = $bureau;
        }

        return $this;
    }

    public function removeBureau(Bureau $bureau): self
    {
        if ($this->bureaus->contains($bureau)) {
            $this->bureaus->removeElement($bureau);
        }

        return $this;
    }

    /**
     * @return Collection|MaterielInformatique[]
     */
    public function getMaterielInformatiques(): Collection
    {
        return $this->materielInformatiques;
    }

    public function addMaterielInformatique(MaterielInformatique $materielInformatique): self
    {
        if (!$this->materielInformatiques->contains($materielInformatique)) {
            $this->materielInformatiques[] = $materielInformatique;
        }

        return $this;
    }

    public function removeMaterielInformatique(MaterielInformatique $materielInformatique): self
    {
        if ($this->materielInformatiques->contains($materielInformatique)) {
            $this->materielInformatiques->removeElement($materielInformatique);
        }

        return $this;
    }
}
