<?php

namespace App\Event\Subscriber;


use App\Entity\Inventaire;
use App\Entity\InventaireInformatique;
use App\Entity\InventaireMobilier;
use App\Entity\Materiel;
use App\Entity\Patrimoine;
use App\Entity\User;
use App\Utils\TokenGenerator;
use App\Utils\Type\Choice\InventaireChoiceType;
use Symfony\Component\EventDispatcher\Event;

class PatrimoineEvent extends Event
{
    const ON_ADD_PATRIMOINE = 'user.add.patrimoine';
    const ON_DELETE_PATRIMOINE = 'user.delete.patrimoine';

    /** @var Patrimoine */
    private $patrimoine;

    /**
     * @return Patrimoine
     */
    public function getPatrimoine(): Patrimoine
    {
        return $this->patrimoine;
    }

    /**
     * @param Patrimoine $patrimoine
     * @return PatrimoineEvent
     */
    public function setPatrimoine(Patrimoine $patrimoine): PatrimoineEvent
    {
        $this->patrimoine = $patrimoine;
        return $this;
    }

    public function addPatrimoine(User $user)
    {
        $this->patrimoine->setUser($user);
        if(is_null($this->patrimoine->getId())){
            $this->patrimoine->setReference(TokenGenerator::getToken(random_int(4,8)));
        }

        $inventaires = $this->patrimoine->getInventaires();
        foreach ($inventaires->toArray() as $inventaire){
            /** @var Materiel $materiel */
            $materiel = $this->getMateriel($inventaire);
            $cas = $inventaire->getCas();
            switch ($cas){
                case InventaireChoiceType::AJOUT:
                    $materiel->setQuantite($materiel->getQuantite() + $inventaire->getQuantite());
                    break;
                case InventaireChoiceType::RETRAIT:
                    $quantite = $materiel->getQuantite() - $inventaire->getQuantite();
                    $materiel->setQuantite($quantite >= 0 ? $quantite : 0);
                    break;
            }
        }
    }

    public function deletePatrimoine()
    {
        $inventaires = $this->patrimoine->getInventaires();
        foreach ($inventaires->toArray() as $inventaire){
            /** @var Materiel $materiel */
            $materiel = $this->getMateriel($inventaire);
            $quantite = $materiel->getQuantite() - $inventaire->getQuantite();
            $materiel->setQuantite($quantite >= 0 ? $quantite : 0);
        }
    }

    private function getMateriel(Inventaire $inventaire)
    {
        $class = get_class($inventaire);

        switch ($class){
            case InventaireInformatique::class:
                return $inventaire->getMaterielInformatique();
            case InventaireMobilier::class:
                return $inventaire->getMaterielMobilier();
        }

    }
}