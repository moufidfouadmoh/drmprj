<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"consommation" = "CConsommation"})
 */
abstract class Conge
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $reference;

    /**
     * @ORM\Column(type="string", length=150)
     * @Gedmo\Slug(fields={"reference","id"})
     */
    protected $slug;

    /**
     * @ORM\Column(type="date")
     */
    protected $datedebut;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $motif;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    protected $delaiaccorde;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CModele", inversedBy="cConsommations")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $cmodele;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="conges")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Situation", inversedBy="conges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $situation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Affectation", inversedBy="conges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $affectation;

    public function __construct(User $user = null,CModele $modele = null)
    {
        if(!is_null($user)){
            $this->user = $user;
        }

        if(!is_null($modele)){
            $this->cmodele = $modele;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDelaiaccorde(): ?\DateInterval
    {
        return $this->delaiaccorde;
    }

    public function setDelaiaccorde(?\DateInterval $delaiaccorde): self
    {
        $this->delaiaccorde = $delaiaccorde;

        return $this;
    }

    public function getCmodele(): ?CModele
    {
        return $this->cmodele;
    }

    public function setCmodele(?CModele $cmodele): self
    {
        $this->cmodele = $cmodele;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSituation(): ?Situation
    {
        return $this->situation;
    }

    public function setSituation(?Situation $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    public function getAffectation(): ?Affectation
    {
        return $this->affectation;
    }

    public function setAffectation(?Affectation $affectation): self
    {
        $this->affectation = $affectation;

        return $this;
    }

    public function getDatefin(\DateInterval $interval)
    {
        $start = clone $this->datedebut;
        $start->sub(new \DateInterval('P1D'));
        $end = clone $start;
        $interval = clone $interval;
        $end = $end->add($interval);
        return $end;
    }
}
