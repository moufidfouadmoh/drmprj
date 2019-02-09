<?php

namespace App\Entity\Includes\Search;

use Doctrine\Common\Collections\Collection;

class IpSearch
{
    /** @var string|null */
    private $address;
    /** @var Collection|null */
    private $bureaus;
    /** @var Collection|null */
    private $agences;

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return Collection|null
     */
    public function getBureaus(): ?Collection
    {
        return $this->bureaus;
    }

    /**
     * @param Collection|null $bureaus
     */
    public function setBureaus(?Collection $bureaus): void
    {
        $this->bureaus = $bureaus;
    }

    /**
     * @return Collection|null
     */
    public function getAgences(): ?Collection
    {
        return $this->agences;
    }

    /**
     * @param Collection|null $agences
     */
    public function setAgences(?Collection $agences): void
    {
        $this->agences = $agences;
    }

}