<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\AgenceRepository")
 */
class Agence
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="agences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="agenceOrigin")
     */
    private $affectationsOrigin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="agenceDest")
     */
    private $affectationsDest;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Bureau", mappedBy="agences")
     */
    private $bureaus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="currentAgence")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ip", mappedBy="agence")
     */
    private $ips;

    public function __construct()
    {
        $this->affectationsOrigin = new ArrayCollection();
        $this->affectationsDest = new ArrayCollection();
        $this->bureaus = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->ips = new ArrayCollection();
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

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

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
            $affectationsOrigin->setAgenceOrigin($this);
        }

        return $this;
    }

    public function removeAffectationsOrigin(Affectation $affectationsOrigin): self
    {
        if ($this->affectationsOrigin->contains($affectationsOrigin)) {
            $this->affectationsOrigin->removeElement($affectationsOrigin);
            // set the owning side to null (unless already changed)
            if ($affectationsOrigin->getAgenceOrigin() === $this) {
                $affectationsOrigin->setAgenceOrigin(null);
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
            $affectationsDest->setAgenceDest($this);
        }

        return $this;
    }

    public function removeAffectationsDest(Affectation $affectationsDest): self
    {
        if ($this->affectationsDest->contains($affectationsDest)) {
            $this->affectationsDest->removeElement($affectationsDest);
            // set the owning side to null (unless already changed)
            if ($affectationsDest->getAgenceDest() === $this) {
                $affectationsDest->setAgenceDest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bureau[]
     */
    public function getBureaus(): Collection
    {
        return $this->bureaus;
    }

    public function addBureau(Bureau $bureau): self
    {
        if (!$this->bureaus->contains($bureau)) {
            $this->bureaus[] = $bureau;
            $bureau->addAgence($this);
        }

        return $this;
    }

    public function removeBureau(Bureau $bureau): self
    {
        if ($this->bureaus->contains($bureau)) {
            $this->bureaus->removeElement($bureau);
            $bureau->removeAgence($this);
        }

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
            $user->setCurrentAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCurrentAgence() === $this) {
                $user->setCurrentAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ip[]
     */
    public function getIps(): Collection
    {
        return $this->ips;
    }

    public function addIp(Ip $ip): self
    {
        if (!$this->ips->contains($ip)) {
            $this->ips[] = $ip;
            $ip->setAgence($this);
        }

        return $this;
    }

    public function removeIp(Ip $ip): self
    {
        if ($this->ips->contains($ip)) {
            $this->ips->removeElement($ip);
            // set the owning side to null (unless already changed)
            if ($ip->getAgence() === $this) {
                $ip->setAgence(null);
            }
        }

        return $this;
    }
}
