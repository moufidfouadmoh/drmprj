<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"direction" = "Direction", "departement" = "Departement", "service" = "Service"})
 */
abstract class Bureau
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100,unique=true)
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=150)
     * @Gedmo\Slug(fields={"nom"})
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $telephone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="currentBureau")
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="bureauOrigin")
     */
    protected $affectationsOrigin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="bureauDest")
     */
    protected $affectationsDest;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Agence", inversedBy="bureaus")
     */
    protected $agences;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\InterventionInterne", mappedBy="bureaus")
     */
    private $interventions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventaireInformatique", mappedBy="bureau")
     */
    private $inventaireInformatiques;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventaireMobilier", mappedBy="bureau")
     */
    private $inventaireMobiliers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ip", mappedBy="bureau")
     */
    private $ips;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->affectationsOrigin = new ArrayCollection();
        $this->affectationsDest = new ArrayCollection();
        $this->agences = new ArrayCollection();
        $this->interventions = new ArrayCollection();
        $this->inventaireInformatiques = new ArrayCollection();
        $this->inventaireMobiliers = new ArrayCollection();
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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
            $user->setCurrentBureau($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCurrentBureau() === $this) {
                $user->setCurrentBureau(null);
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
            $affectationsOrigin->setBureauOrigin($this);
        }

        return $this;
    }

    public function removeAffectationsOrigin(Affectation $affectationsOrigin): self
    {
        if ($this->affectationsOrigin->contains($affectationsOrigin)) {
            $this->affectationsOrigin->removeElement($affectationsOrigin);
            // set the owning side to null (unless already changed)
            if ($affectationsOrigin->getBureauOrigin() === $this) {
                $affectationsOrigin->setBureauOrigin(null);
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
            $affectationsDest->setBureauDest($this);
        }

        return $this;
    }

    public function removeAffectationsDest(Affectation $affectationsDest): self
    {
        if ($this->affectationsDest->contains($affectationsDest)) {
            $this->affectationsDest->removeElement($affectationsDest);
            // set the owning side to null (unless already changed)
            if ($affectationsDest->getBureauDest() === $this) {
                $affectationsDest->setBureauDest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Agence[]
     */
    public function getAgences(): Collection
    {
        return $this->agences;
    }

    public function addAgence(Agence $agence): self
    {
        if (!$this->agences->contains($agence)) {
            $this->agences[] = $agence;
        }

        return $this;
    }

    public function removeAgence(Agence $agence): self
    {
        if ($this->agences->contains($agence)) {
            $this->agences->removeElement($agence);
        }

        return $this;
    }

    /**
     * @return Collection|InterventionInterne[]
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(InterventionInterne $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->addBureau($this);
        }

        return $this;
    }

    public function removeIntervention(InterventionInterne $intervention): self
    {
        if ($this->interventions->contains($intervention)) {
            $this->interventions->removeElement($intervention);
            $intervention->removeBureau($this);
        }

        return $this;
    }

    /**
     * @return Collection|InventaireInformatique[]
     */
    public function getInventaireInformatiques(): Collection
    {
        return $this->inventaireInformatiques;
    }

    public function addInventaireInformatique(InventaireInformatique $inventaireInformatique): self
    {
        if (!$this->inventaireInformatiques->contains($inventaireInformatique)) {
            $this->inventaireInformatiques[] = $inventaireInformatique;
            $inventaireInformatique->setBureau($this);
        }

        return $this;
    }

    public function removeInventaireInformatique(InventaireInformatique $inventaireInformatique): self
    {
        if ($this->inventaireInformatiques->contains($inventaireInformatique)) {
            $this->inventaireInformatiques->removeElement($inventaireInformatique);
            // set the owning side to null (unless already changed)
            if ($inventaireInformatique->getBureau() === $this) {
                $inventaireInformatique->setBureau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InventaireMobilier[]
     */
    public function getInventaireMobiliers(): Collection
    {
        return $this->inventaireMobiliers;
    }

    public function addInventaireMobilier(InventaireMobilier $inventaireMobilier): self
    {
        if (!$this->inventaireMobiliers->contains($inventaireMobilier)) {
            $this->inventaireMobiliers[] = $inventaireMobilier;
            $inventaireMobilier->setBureau($this);
        }

        return $this;
    }

    public function removeInventaireMobilier(InventaireMobilier $inventaireMobilier): self
    {
        if ($this->inventaireMobiliers->contains($inventaireMobilier)) {
            $this->inventaireMobiliers->removeElement($inventaireMobilier);
            // set the owning side to null (unless already changed)
            if ($inventaireMobilier->getBureau() === $this) {
                $inventaireMobilier->setBureau(null);
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
            $ip->setBureau($this);
        }

        return $this;
    }

    public function removeIp(Ip $ip): self
    {
        if ($this->ips->contains($ip)) {
            $this->ips->removeElement($ip);
            // set the owning side to null (unless already changed)
            if ($ip->getBureau() === $this) {
                $ip->setBureau(null);
            }
        }

        return $this;
    }
}
