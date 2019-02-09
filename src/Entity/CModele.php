<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CModeleRepository")
 */
class CModele
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150)
     * @Gedmo\Slug(fields={"nom","id"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\Column(type="boolean")
     */
    private $justificatif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $service;

    /**
     * @ORM\Column(type="boolean")
     */
    private $departement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $direction;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fixe;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $delaimin;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $delaimax;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Statut", inversedBy="cModeles")
     */
    private $statuts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CConsommation", mappedBy="cmodele")
     */
    private $cConsommations;

    public function __construct()
    {
        $this->statuts = new ArrayCollection();
        $this->cConsommations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getJustificatif(): ?bool
    {
        return $this->justificatif;
    }

    public function setJustificatif(bool $justificatif): self
    {
        $this->justificatif = $justificatif;

        return $this;
    }

    public function getService(): ?bool
    {
        return $this->service;
    }

    public function setService(bool $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getDepartement(): ?bool
    {
        return $this->departement;
    }

    public function setDepartement(bool $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getDirection(): ?bool
    {
        return $this->direction;
    }

    public function setDirection(bool $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getFixe(): ?bool
    {
        return $this->fixe;
    }

    public function setFixe(?bool $fixe): self
    {
        $this->fixe = $fixe;

        return $this;
    }

    /**
     * @return Collection|Statut[]
     */
    public function getStatuts(): Collection
    {
        return $this->statuts;
    }

    public function addStatut(Statut $statut): self
    {
        if (!$this->statuts->contains($statut)) {
            $this->statuts[] = $statut;
        }

        return $this;
    }

    public function removeStatut(Statut $statut): self
    {
        if ($this->statuts->contains($statut)) {
            $this->statuts->removeElement($statut);
        }

        return $this;
    }

    /**
     * @return Collection|CConsommation[]
     */
    public function getCConsommations(): Collection
    {
        return $this->cConsommations;
    }

    public function addCConsommation(CConsommation $cConsommation): self
    {
        if (!$this->cConsommations->contains($cConsommation)) {
            $this->cConsommations[] = $cConsommation;
            $cConsommation->setCmodele($this);
        }

        return $this;
    }

    public function removeCConsommation(CConsommation $cConsommation): self
    {
        if ($this->cConsommations->contains($cConsommation)) {
            $this->cConsommations->removeElement($cConsommation);
            // set the owning side to null (unless already changed)
            if ($cConsommation->getCmodele() === $this) {
                $cConsommation->setCmodele(null);
            }
        }

        return $this;
    }

    public function getDelaimin(): ?\DateInterval
    {
        return $this->delaimin;
    }

    public function setDelaimin(?\DateInterval $delaimin): self
    {
        $this->delaimin = $delaimin;

        return $this;
    }

    public function getDelaimax(): ?\DateInterval
    {
        return $this->delaimax;
    }

    public function setDelaimax(?\DateInterval $delaimax): self
    {
        $this->delaimax = $delaimax;

        return $this;
    }
}
