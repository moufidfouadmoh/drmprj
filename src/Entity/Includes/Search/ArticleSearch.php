<?php

namespace App\Entity\Includes\Search;


class ArticleSearch
{
    /** @var string|null */
    private $title;
    /** @var \DateTime|null */
    private $debut;
    /** @var \DateTime|null */
    private $fin;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return \DateTime|null
     */
    public function getDebut(): ?\DateTime
    {
        return $this->debut;
    }

    /**
     * @param \DateTime|null $debut
     */
    public function setDebut(?\DateTime $debut): void
    {
        $this->debut = $debut;
    }

    /**
     * @return \DateTime|null
     */
    public function getFin(): ?\DateTime
    {
        return $this->fin;
    }

    /**
     * @param \DateTime|null $fin
     */
    public function setFin(?\DateTime $fin): void
    {
        $this->fin = $fin;
    }
}