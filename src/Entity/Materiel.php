<?php

namespace App\Entity;


use App\Utils\TokenGenerator;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterielRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"informatique" = "MaterielInformatique","mobilier" = "MaterielMobilier"})
 */
abstract class Materiel
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Gedmo\Slug(fields={"reference"})
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipement", inversedBy="materiels", cascade={"persist"})
     */
    protected $equipement;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    protected $quantite;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $unite;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $reference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipement(): ?Equipement
    {
        return $this->equipement;
    }

    public function setEquipement(?Equipement $equipement): self
    {
        $this->equipement = $equipement;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function __construct()
    {
        $this->reference = TokenGenerator::getToken(random_int(5,7));
    }
}
