<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AffectationRepository")
 */
class Affectation
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="user"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="-")
     *      }),
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="agenceDest"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="-")
     *      })
     * }, separator="-", fields={"id"})
     * @ORM\Column(length=100, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fonction", inversedBy="affectationsOrigin")
     */
    private $fonctionOrigin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fonction", inversedBy="affectationsDest")
     */
    private $fonctionDest;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bureau", inversedBy="affectationsOrigin")
     */
    private $bureauOrigin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bureau", inversedBy="affectationsDest")
     */
    private $bureauDest;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agence", inversedBy="affectationsOrigin")
     */
    private $agenceOrigin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agence", inversedBy="affectationsDest")
     */
    private $agenceDest;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affectations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CConsommation", mappedBy="affectation")
     */
    private $conges;

    public function __construct(User $user = null)
    {
        if(!is_null($user)){
            $this->user = $user;
            $this->fonctionOrigin = $user->getCurrentFonction();
            $this->bureauOrigin = $user->getCurrentBureau();
            $this->agenceOrigin = $user->getCurrentAgence();
        }
        $this->conges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getFonctionOrigin(): ?Fonction
    {
        return $this->fonctionOrigin;
    }

    public function setFonctionOrigin(?Fonction $fonctionOrigin): self
    {
        $this->fonctionOrigin = $fonctionOrigin;

        return $this;
    }

    public function getFonctionDest(): ?Fonction
    {
        return $this->fonctionDest;
    }

    public function setFonctionDest(?Fonction $fonctionDest): self
    {
        $this->fonctionDest = $fonctionDest;

        return $this;
    }

    public function getBureauOrigin(): ?Bureau
    {
        return $this->bureauOrigin;
    }

    public function setBureauOrigin(?Bureau $bureauOrigin): self
    {
        $this->bureauOrigin = $bureauOrigin;

        return $this;
    }

    public function getBureauDest(): ?Bureau
    {
        return $this->bureauDest;
    }

    public function setBureauDest(?Bureau $bureauDest): self
    {
        $this->bureauDest = $bureauDest;

        return $this;
    }

    public function getAgenceOrigin(): ?Agence
    {
        return $this->agenceOrigin;
    }

    public function setAgenceOrigin(?Agence $agenceOrigin): self
    {
        $this->agenceOrigin = $agenceOrigin;

        return $this;
    }

    public function getAgenceDest(): ?Agence
    {
        return $this->agenceDest;
    }

    public function setAgenceDest(?Agence $agenceDest): self
    {
        $this->agenceDest = $agenceDest;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

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

    /**
     * @return Collection|CConsommation[]
     */
    public function getConges(): Collection
    {
        return $this->conges;
    }

    public function addConge(CConsommation $conge): self
    {
        if (!$this->conges->contains($conge)) {
            $this->conges[] = $conge;
            $conge->setAffectation($this);
        }

        return $this;
    }

    public function removeConge(CConsommation $conge): self
    {
        if ($this->conges->contains($conge)) {
            $this->conges->removeElement($conge);
            // set the owning side to null (unless already changed)
            if ($conge->getAffectation() === $this) {
                $conge->setAffectation(null);
            }
        }

        return $this;
    }
}
