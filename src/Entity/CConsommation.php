<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CConsommationRepository")
 */
class CConsommation extends Conge
{
    /**
     * @ORM\Column(type="string", length=5)
     */
    private $annee;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fin;

    public function __construct(User $user = null,CModele $modele = null)
    {
        $now = new \DateTime();
        parent::__construct($user,$modele);
        $this->annee = $now->format('Y');
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $datefin): self
    {
        $this->fin = $datefin;

        return $this;
    }
}
