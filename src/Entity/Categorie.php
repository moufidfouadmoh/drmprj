<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
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
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="currentCategorie")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cadrage", mappedBy="categorie")
     */
    private $cadrages;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->cadrages = new ArrayCollection();
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
            $user->setCurrentCategorie($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCurrentCategorie() === $this) {
                $user->setCurrentCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cadrage[]
     */
    public function getCadrages(): Collection
    {
        return $this->cadrages;
    }

    public function addCadrage(Cadrage $cadrage): self
    {
        if (!$this->cadrages->contains($cadrage)) {
            $this->cadrages[] = $cadrage;
            $cadrage->setCategorie($this);
        }

        return $this;
    }

    public function removeCadrage(Cadrage $cadrage): self
    {
        if ($this->cadrages->contains($cadrage)) {
            $this->cadrages->removeElement($cadrage);
            // set the owning side to null (unless already changed)
            if ($cadrage->getCategorie() === $this) {
                $cadrage->setCategorie(null);
            }
        }

        return $this;
    }
}
