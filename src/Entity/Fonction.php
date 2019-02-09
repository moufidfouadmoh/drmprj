<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\FonctionRepository")
 */
class Fonction
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
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="currentFonction")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="fonctionOrigin")
     */
    private $affectationsOrigin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="fonctionDest")
     */
    private $affectationsDest;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->affectationsOrigin = new ArrayCollection();
        $this->affectationsDest = new ArrayCollection();
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
            $user->setCurrentFonction($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCurrentFonction() === $this) {
                $user->setCurrentFonction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectationsOrigin(): Collection
    {
        return $this->affectationsOrigin;
    }

    public function addAffectationsOrigin(Affectation $affectationsOrigin): self
    {
        if (!$this->affectationsOrigin->contains($affectationsOrigin)) {
            $this->affectationsOrigin[] = $affectationsOrigin;
            $affectationsOrigin->setFonctionOrigin($this);
        }

        return $this;
    }

    public function removeAffectationsOrigin(Affectation $affectationsOrigin): self
    {
        if ($this->affectationsOrigin->contains($affectationsOrigin)) {
            $this->affectationsOrigin->removeElement($affectationsOrigin);
            // set the owning side to null (unless already changed)
            if ($affectationsOrigin->getFonctionOrigin() === $this) {
                $affectationsOrigin->setFonctionOrigin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectationsDest(): Collection
    {
        return $this->affectationsDest;
    }

    public function addAffectationsDest(Affectation $affectationsDest): self
    {
        if (!$this->affectationsDest->contains($affectationsDest)) {
            $this->affectationsDest[] = $affectationsDest;
            $affectationsDest->setFonctionDest($this);
        }

        return $this;
    }

    public function removeAffectationsDest(Affectation $affectationsDest): self
    {
        if ($this->affectationsDest->contains($affectationsDest)) {
            $this->affectationsDest->removeElement($affectationsDest);
            // set the owning side to null (unless already changed)
            if ($affectationsDest->getFonctionDest() === $this) {
                $affectationsDest->setFonctionDest(null);
            }
        }

        return $this;
    }
}
