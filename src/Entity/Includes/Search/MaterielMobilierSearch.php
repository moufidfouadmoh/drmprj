<?php

namespace App\Entity\Includes\Search;


use Doctrine\Common\Collections\Collection;

class MaterielMobilierSearch
{
    /** @var Collection|null */
    private $equipements;

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
}