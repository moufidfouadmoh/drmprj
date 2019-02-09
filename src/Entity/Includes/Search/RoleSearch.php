<?php

namespace App\Entity\Includes\Search;


class RoleSearch
{
    /** @var string|null */
    private $role;

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }
}