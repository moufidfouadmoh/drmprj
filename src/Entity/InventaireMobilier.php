<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InventaireMobilierRepository")
 */
class InventaireMobilier extends Inventaire
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MaterielMobilier", inversedBy="inventaireMobiliers")
     */
    private $materielMobilier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PatrimoineMobilier", inversedBy="inventaires")
     */
    private $patrimoineMobilier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bureau", inversedBy="inventaireMobiliers")
     */
    private $bureau;

    public function getMaterielMobilier(): ?MaterielMobilier
    {
        return $this->materielMobilier;
    }

    public function setMaterielMobilier(?MaterielMobilier $materielMobilier): self
    {
        $this->materielMobilier = $materielMobilier;

        return $this;
    }

    public function getPatrimoineMobilier(): ?PatrimoineMobilier
    {
        return $this->patrimoineMobilier;
    }

    public function setPatrimoineMobilier(?PatrimoineMobilier $patrimoineMobilier): self
    {
        $this->patrimoineMobilier = $patrimoineMobilier;

        return $this;
    }

    public function getBureau(): ?Bureau
    {
        return $this->bureau;
    }

    public function setBureau(?Bureau $bureau): self
    {
        $this->bureau = $bureau;

        return $this;
    }
}
