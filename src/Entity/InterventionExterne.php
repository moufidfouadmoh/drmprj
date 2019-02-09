<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionExterneRepository")
 */
class InterventionExterne extends Intervention
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $equipement;

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEquipement(): ?string
    {
        return $this->equipement;
    }

    public function setEquipement(string $equipement): self
    {
        $this->equipement = $equipement;

        return $this;
    }
}
