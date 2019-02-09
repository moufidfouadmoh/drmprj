<?php

namespace App\Utils\Twig\Functions;


use App\Entity\Cadre;
use App\Entity\Categorie;
use App\Entity\CDemande;
use App\Entity\Classement;
use App\Entity\CModele;
use App\Entity\Departement;
use App\Entity\Fonction;
use App\Entity\Mutation;
use App\Entity\Service;
use App\Entity\Siege;
use App\Entity\Situation;
use App\Entity\Statut;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\Type\CadreFormType;
use App\Form\Type\CategorieFormType;
use App\Form\Type\CDemandeFormType;
use App\Form\Type\ClassementFormType;
use App\Form\Type\CModeleFormType;
use App\Form\Type\DepartementFormType;
use App\Form\Type\FonctionFormType;
use App\Form\Type\MutationFormType;
use App\Form\Type\ServiceFormType;
use App\Form\Type\SiegeFormType;
use App\Form\Type\SituationFormType;
use App\Form\Type\StatutFormType;
use App\Form\Type\UserFormType;
use App\Form\Type\VilleFormType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Twig\TwigFunction;

trait FormFunctionTrait
{
    /** @var FormFactory */
    private $form;

    /** @var Router */
    private $router;

    private function formFunctionTrait()
    {
        $functions = [
            /*new TwigFunction('edit_ville', [$this, 'displayVilleEditForm']),
            new TwigFunction('edit_siege', [$this, 'displaySiegeEditForm']),
            new TwigFunction('edit_fonction', [$this, 'displayFonctionEditForm']),
            new TwigFunction('edit_categorie', [$this, 'displayCategorieEditForm']),
            new TwigFunction('edit_service', [$this, 'displayServiceEditForm']),
            new TwigFunction('edit_departement', [$this, 'displayDepartementEditForm']),
            new TwigFunction('edit_statut', [$this, 'displayStatutEditForm']),
            new TwigFunction('edit_cmodele', [$this, 'displayCModeleEditForm']),*/
            new TwigFunction('form_delete', [$this, 'createDeleteForm']),

            /*new TwigFunction('edit_user', [$this, 'displayUserEditForm']),
            new TwigFunction('mute_user', [$this, 'displayMuteUserForm']),
            new TwigFunction('edit_mutation', [$this, 'displayEditMutationForm']),
            new TwigFunction('situate_user', [$this, 'displaySituateUserForm']),
            new TwigFunction('edit_situation', [$this, 'displayEditSituationForm']),
            new TwigFunction('cadrate_user', [$this, 'displayCadrateUserForm']),
            new TwigFunction('edit_cadre', [$this, 'displayEditCadreForm']),
            new TwigFunction('classify_user', [$this, 'displayClassifyUserForm']),
            new TwigFunction('edit_classement', [$this, 'displayEditClassementForm']),
            new TwigFunction('demande_cmodele', [$this, 'displayCDemandeModeleForm']),
            new TwigFunction('create_user_cmodele', [$this, 'displayCDemandeUserModeleForm']),
            new TwigFunction('accord_cdemande', [$this, 'displayAccordCDemandeForm'])*/
        ];

        return $functions;
    }

    public function createDeleteForm($id, $route)
    {
        return $this->form->createBuilder(FormType::class,null, array('attr' => array('id' => 'delete')))
            ->setAction($this->router->generate($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
            ->createView()
            ;
    }
}