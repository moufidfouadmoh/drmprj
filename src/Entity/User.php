<?php

namespace App\Entity;

use App\Utils\Entity\User\CongeAble;
use App\Utils\Entity\User\RecrutementAble;
use App\Utils\Entity\User\RetraiteAble;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="username",message="user.username.unique")
 * @UniqueEntity(fields="email",message="user.email.unique")
 */
class User implements UserInterface, \Serializable
{
    use RecrutementAble,RetraiteAble,CongeAble;
    use TimestampableEntity;
    const ADMIN = 'admin';
    const PASSWORD = 'password';
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
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
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=100,unique=true)
     * @Assert\NotBlank(message="username.not.blank")
     * @Assert\Length(min=2,minMessage="user.username.length.min",max=6,maxMessage="user.username.length.max")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100,unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;


    /**
     * @var string
     * @Gedmo\Slug(fields={"prenom","username","nom"})
     * @ORM\Column(type="string",length=100,nullable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $telephone1;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $telephone2;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $telephone3;

    /**
     * @ORM\Column(type="date")
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $lieunaissance;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Formation", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $formations;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordRequestedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adressepostale;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bureau", inversedBy="users")
     */
    private $currentBureau;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="users")
     */
    private $currentCategorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Statut", inversedBy="users")
     */
    private $currentStatut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fonction", inversedBy="users")
     */
    private $currentFonction;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    protected $currentgroupe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $currentniveau;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $currentechelon;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="user", orphanRemoval=true)
     */
    private $affectations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Classement", mappedBy="user", orphanRemoval=true)
     */
    private $classements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Situation", mappedBy="user")
     */
    protected $situations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cadrage", mappedBy="user")
     */
    private $cadrages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Conge", mappedBy="user")
     */
    protected $conges;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agence", inversedBy="users")
     */
    private $currentAgence;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $daterecrutement;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Intervention", mappedBy="users")
     */
    private $interventions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Patrimoine", mappedBy="user")
     */
    private $patrimoines;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $depart;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user")
     */
    private $articles;

    public function __construct()
    {
        $this->enabled = true;
        $this->roles = [self::ROLE_DEFAULT];
        $this->formations = new ArrayCollection();
        $this->affectations = new ArrayCollection();
        $this->classements = new ArrayCollection();
        $this->situations = new ArrayCollection();
        $this->conges = new ArrayCollection();
        $this->cadrages = new ArrayCollection();
        $this->interventions = new ArrayCollection();
        $this->patrimoines = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getNomPrenom()
    {
        return $this->nom . ' ' . $this->prenom;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getTelephone1(): ?string
    {
        return $this->telephone1;
    }

    public function setTelephone1(string $telephone1): self
    {
        $this->telephone1 = $telephone1;

        return $this;
    }

    public function getTelephone2(): ?string
    {
        return $this->telephone2;
    }

    public function setTelephone2(?string $telephone2): self
    {
        $this->telephone2 = $telephone2;

        return $this;
    }

    public function getTelephone3(): ?string
    {
        return $this->telephone3;
    }

    public function setTelephone3(?string $telephone3): self
    {
        $this->telephone3 = $telephone3;

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

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {

    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->enabled
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->id,
            $this->username,
            $this->password,
            $this->enabled) = unserialize($serialized);
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getLieunaissance(): ?string
    {
        return $this->lieunaissance;
    }

    public function setLieunaissance(?string $lieunaissance): self
    {
        $this->lieunaissance = $lieunaissance;

        return $this;
    }

    /**
     * @return Collection|Formation[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setUser($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
            // set the owning side to null (unless already changed)
            if ($formation->getUser() === $this) {
                $formation->setUser(null);
            }
        }

        return $this;
    }

    public function setRoles(array $roles): self
    {
        if (!in_array(self::ROLE_DEFAULT, $roles))
        {
            $roles[] = self::ROLE_DEFAULT;
        }
        foreach ($roles as $role)
        {
            if(substr($role, 0, 5) !== 'ROLE_') {
                throw new \InvalidArgumentException("Chaque rôle doit commencer par 'ROLE_'");
            }
        }
        $this->roles = $roles;

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

    public function getPasswordRequestedAt(): ?\DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt(?\DateTimeInterface $passwordRequestedAt): self
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getAdressepostale(): ?string
    {
        return $this->adressepostale;
    }

    public function setAdressepostale(?string $adressepostale): self
    {
        $this->adressepostale = $adressepostale;

        return $this;
    }

    public function getCurrentBureau(): ?Bureau
    {
        return $this->currentBureau;
    }

    public function setCurrentBureau(?Bureau $currentBureau): self
    {
        $this->currentBureau = $currentBureau;

        return $this;
    }

    public function getCurrentCategorie(): ?Categorie
    {
        return $this->currentCategorie;
    }

    public function setCurrentCategorie(?Categorie $currentCategorie): self
    {
        $this->currentCategorie = $currentCategorie;

        return $this;
    }

    public function getCurrentStatut(): ?Statut
    {
        return $this->currentStatut;
    }

    public function setCurrentStatut(?Statut $currentStatut): self
    {
        $this->currentStatut = $currentStatut;

        return $this;
    }

    public function getCurrentFonction(): ?Fonction
    {
        return $this->currentFonction;
    }

    public function setCurrentFonction(?Fonction $currentFonction): self
    {
        $this->currentFonction = $currentFonction;

        return $this;
    }


    /**
     * @return Collection|Affectation[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setUser($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getUser() === $this) {
                $affectation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Classement[]
     */
    public function getClassements(): Collection
    {
        return $this->classements;
    }

    public function addClassement(Classement $classement): self
    {
        if (!$this->classements->contains($classement)) {
            $this->classements[] = $classement;
            $classement->setUser($this);
        }

        return $this;
    }

    public function removeClassement(Classement $classement): self
    {
        if ($this->classements->contains($classement)) {
            $this->classements->removeElement($classement);
            // set the owning side to null (unless already changed)
            if ($classement->getUser() === $this) {
                $classement->setUser(null);
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
            $situation->setUser($this);
        }

        return $this;
    }

    public function removeSituation(Situation $situation): self
    {
        if ($this->situations->contains($situation)) {
            $this->situations->removeElement($situation);
            // set the owning side to null (unless already changed)
            if ($situation->getUser() === $this) {
                $situation->setUser(null);
            }
        }

        return $this;
    }

    public function getCurrentAgence(): ?Agence
    {
        return $this->currentAgence;
    }

    public function setCurrentAgence(?Agence $currentAgence): self
    {
        $this->currentAgence = $currentAgence;

        return $this;
    }

    public function getCurrentgroupe(): ?string
    {
        return $this->currentgroupe;
    }

    public function setCurrentgroupe(?string $currentgroupe): self
    {
        $this->currentgroupe = $currentgroupe;

        return $this;
    }

    public function getCurrentniveau(): ?int
    {
        return $this->currentniveau;
    }

    public function setCurrentniveau(?int $currentniveau): self
    {
        $this->currentniveau = $currentniveau;

        return $this;
    }

    public function getCurrentechelon(): ?int
    {
        return $this->currentechelon;
    }

    public function setCurrentechelon(?int $currentechelon): self
    {
        $this->currentechelon = $currentechelon;

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
            $cadrage->setUser($this);
        }

        return $this;
    }

    public function removeCadrage(Cadrage $cadrage): self
    {
        if ($this->cadrages->contains($cadrage)) {
            $this->cadrages->removeElement($cadrage);
            // set the owning side to null (unless already changed)
            if ($cadrage->getUser() === $this) {
                $cadrage->setUser(null);
            }
        }

        return $this;
    }

    public function getDaterecrutement(): ?\DateTimeInterface
    {
        return $this->daterecrutement;
    }

    public function setDaterecrutement(?\DateTimeInterface $daterecrutement): self
    {
        $this->daterecrutement = $daterecrutement;

        return $this;
    }

    /**
     * @return Collection|Conge[]
     */
    public function getConges(): Collection
    {
        return $this->conges;
    }

    public function addConge(Conge $conge): self
    {
        if (!$this->conges->contains($conge)) {
            $this->conges[] = $conge;
            $conge->setUser($this);
        }

        return $this;
    }

    public function removeConge(Conge $conge): self
    {
        if ($this->conges->contains($conge)) {
            $this->conges->removeElement($conge);
            // set the owning side to null (unless already changed)
            if ($conge->getUser() === $this) {
                $conge->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Intervention[]
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->addUser($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->contains($intervention)) {
            $this->interventions->removeElement($intervention);
            $intervention->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Patrimoine[]
     */
    public function getPatrimoines(): Collection
    {
        return $this->patrimoines;
    }

    public function addPatrimoine(Patrimoine $patrimoine): self
    {
        if (!$this->patrimoines->contains($patrimoine)) {
            $this->patrimoines[] = $patrimoine;
            $patrimoine->setUser($this);
        }

        return $this;
    }

    public function removePatrimoine(Patrimoine $patrimoine): self
    {
        if ($this->patrimoines->contains($patrimoine)) {
            $this->patrimoines->removeElement($patrimoine);
            // set the owning side to null (unless already changed)
            if ($patrimoine->getUser() === $this) {
                $patrimoine->setUser(null);
            }
        }

        return $this;
    }

    public function getDepart(): ?bool
    {
        return $this->depart;
    }

    public function setDepart(?bool $depart): self
    {
        $this->depart = $depart;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }
}
