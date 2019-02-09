<?php

namespace App\Form\Type;

use App\Entity\Bureau;
use App\Entity\InventaireInformatique;
use App\Entity\MaterielInformatique;
use App\Repository\MaterielInformatiqueRepository;
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

class InventaireInformatiqueFormType extends AbstractType
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
            ->add('materielInformatique',EntityType::class,[
                'class' => MaterielInformatique::class,
                'query_builder' => function(MaterielInformatiqueRepository $repository){
                    $qb = $repository->createQueryBuilder('mi');
                    $qb
                        ->leftJoin('mi.equipement','equipement')
                        ->addSelect('equipement')
                        ->leftJoin('mi.marque','marque')
                        ->addSelect('marque')
                    ;
                    return $qb;
                },
                'choice_label' => function(MaterielInformatique $materielInformatique){
                    return $materielInformatique->getEquipement()->getNom() . ' / ' . $materielInformatique->getMarque()->getNom();
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
            'data_class' => InventaireInformatique::class,
        ]);
    }
}
