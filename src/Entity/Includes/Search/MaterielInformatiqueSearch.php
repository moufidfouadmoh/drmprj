<?php

namespace App\Entity\Includes\Search;


use Doctrine\Common\Collections\Collection;

class MaterielInformatiqueSearch
{
    /** @var Collection|null */
    private $equipements;

    /** @var Collection|null */
    private $marques;

    /**
     * @return Collection|null
     */
    public function getEquipements(): ?Collection
    {
        return $this->equipements;
    }

    /**
     * @param Collection|null $equipements
     */
    public function setEquipements(?Collection $equipements): void
    {
        $this->equipements = $equipements;
    }

    /**
     * @return Collection|null
     */
    public function getMarques(): ?Collection
    {
        return $this->marques;
    }

    /**
     * @param Collection|null $marques
     */
    public function setMarques(?Collection $marques): void
    {
        $this->marques = $marques;
    }

}