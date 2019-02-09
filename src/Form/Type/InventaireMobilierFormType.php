<?php

namespace App\Form\Type;

use App\Entity\Bureau;
use App\Entity\InventaireMobilier;
use App\Entity\MaterielMobilier;
use App\Repository\MaterielMobilierRepository;
use App\Utils\Type\Choice\IleChoiceType;
use App\Utils\Type\Choice\InventaireChoiceType;
use Doctrine\ORM\EntityRepository;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventaireMobilierFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite',NumberType::class,[
                'label' => 'app.inventaire.forms.fields.quantite',
            ])
            ->add('detail',FroalaEditorType::class,array(
                'required' => false,
                'label' => 'app.inventaire.forms.fields.detail',
                'pluginsEnabled' => ['file','lists'],
                'fileAllowedTypes' => ['application/pdf','application/msword','jpeg', 'jpg','png']
            ))
            ->add('cas',ChoiceType::class,[
                'choices' => InventaireChoiceType::getCas(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.inventaire.forms.fields.cas'
            ])
            ->add('etat',ChoiceType::class,[
                'choices' => InventaireChoiceType::getEtats(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.inventaire.forms.fields.etat'
            ])
            ->add('materielMobilier',EntityType::class,[
                'class' => MaterielMobilier::class,
                'query_builder' => function(MaterielMobilierRepository $repository){
                    $qb = $repository->createQueryBuilder('mm');
                    $qb
                        ->leftJoin('mm.equipement','equipement')
                        ->addSelect('equipement')
                    ;
                    return $qb;
                },
                'choice_label' => function(MaterielMobilier $materielMobilier){
                    $modele = !is_null($materielMobilier->getModele()) ? ' / ' . $materielMobilier->getModele() : '';
                    return $materielMobilier->getEquipement()->getNom() . $modele;
                },
                'label' => 'app.inventaire.forms.fields.materiel.label'
            ])
            ->add('bureau',EntityType::class,[
                'class' => Bureau::class,
                'query_builder' => function(EntityRepository $repository){
                    $qb = $repository->createQueryBuilder('bureau')->from(Bureau::class,'b');
                    $qb
                        ->leftJoin('bureau.agences','agences')
                        ->addSelect('agences')
                        ->leftJoin('agences.lieu','lieu')
                        ->addSelect('lieu')
                        ->andWhere('lieu.ile = :ile')
                        ->setParameter('ile',IleChoiceType::MWALI)
                    ;
                    return $qb;
                },
                'choice_label' => 'nom',
                'label' => 'app.inventaire.forms.fields.bureau.label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InventaireMobilier::class,
        ]);
    }
}
