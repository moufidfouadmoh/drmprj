<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\StatutRepository")
 */
class Statut
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100,unique=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150)
     * @Gedmo\Slug(fields={"nom"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="currentStatut")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Situation", mappedBy="statut")
     */
    private $situations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CModele", mappedBy="statuts")
     */
    private $cModeles;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->situations = new ArrayCollection();
        $this->cModeles = new ArrayCollection();
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

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCurrentStatut($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCurrentStatut() === $this) {
                $user->setCurrentStatut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Situation[]
     */
    public function getSituations(): Collection
    {
        return $this->situations;
    }

    public function addSituation(Situation $situation): self
    {
        if (!$this->situations->contains($situation)) {
            $this->situations[] = $situation;
            $situation->setStatut($this);
        }

        return $this;
    }

    public function removeSituation(Situation $situation): self
    {
        if ($this->situations->contains($situation)) {
            $this->situations->removeElement($situation);
            // set the owning side to null (unless already changed)
            if ($situation->getStatut() === $this) {
                $situation->setStatut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CModele[]
     */
    public function getCModeles(): Collection
    {
        return $this->cModeles;
    }

    public function addCModele(CModele $cModele): self
    {
        if (!$this->cModeles->contains($cModele)) {
            $this->cModeles[] = $cModele;
            $cModele->addStatut($this);
        }

        return $this;
    }

    public function removeCModele(CModele $cModele): self
    {
        if ($this->cModeles->contains($cModele)) {
            $this->cModeles->removeElement($cModele);
            $cModele->removeStatut($this);
        }

        return $this;
    }
}
