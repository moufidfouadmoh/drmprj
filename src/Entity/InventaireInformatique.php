<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Utils\Type\Choice\InventaireChoiceType;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InventaireInformatiqueRepository")
 */
class InventaireInformatique extends Inventaire
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MaterielInformatique", inversedBy="inventaireInformatiques")
     */
    private $materielInformatique;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bureau", inversedBy="inventaireInformatiques")
     */
    private $bureau;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PatrimoineInformatique", inversedBy="inventaires")
     */
    private $patrimoineInformatique;

    public function __construct()
    {
        $this->cas = InventaireChoiceType::AJOUT;
        $this->etat = InventaireChoiceType::UTILISATION;
        $this->quantite = 0;
    }

    public function getBureau(): ?Bureau
    {
        return $this->bureau;
    }

    public function setBureau(?Bureau $bureau): self
    {
        $this->bureau = $bureau;

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

    public function getMaterielInformatique(): ?MaterielInformatique
    {
        return $this->materielInformatique;
    }

    public function setMaterielInformatique(?MaterielInformatique $materiel): self
    {
        $this->materielInformatique = $materiel;

        return $this;
    }

    public function getPatrimoineInformatique(): ?PatrimoineInformatique
    {
        return $this->patrimoineInformatique;
    }

    public function setPatrimoineInformatique(?PatrimoineInformatique $patrimoineInformatique): self
    {
        $this->patrimoineInformatique = $patrimoineInformatique;

        return $this;
    }
}
