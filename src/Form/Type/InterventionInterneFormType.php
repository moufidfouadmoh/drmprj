<?php

namespace App\Form\Type;

use App\Entity\Bureau;
use App\Entity\InterventionInterne;
use App\Entity\MaterielInformatique;
use App\Entity\User;
use App\Repository\MaterielInformatiqueRepository;
use App\Repository\UserRepository;
use App\Utils\Type\Choice\IleChoiceType;
use Doctrine\ORM\EntityRepository;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionInterneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'app.intervention.forms.fields.date'
            ])
            ->add('probleme',TextType::class,[
                'label' => 'app.intervention.forms.fields.probleme'
            ])
            ->add('materielInformatiques',EntityType::class,[
                'class' => MaterielInformatique::class,
                'multiple' => true,
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
                'label' => 'app.intervention.forms.fields.materiels.label'
            ])
            ->add('solution',TextType::class,[
                'label' => 'app.intervention.forms.fields.solution'
            ])
            ->add('bureaus',EntityType::class,[
                'class' => Bureau::class,
                'multiple' => true,
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
                'label' => 'app.intervention.forms.fields.bureaus.label'
            ])
            ->add('users',EntityType::class,[
                'class' => User::class,
                'multiple' => true,
                'query_builder' => function(UserRepository $repository){
                    $qb = $repository->createQueryBuilder('user');
                    $qb
                        ->andWhere('user.username != :admin')
                        ->setParameter('admin',User::ADMIN)
                    ;
                    return $qb;
                },
                'choice_label' => function(User $user){
                    return $user->getNomPrenom();
                },
                'label' => 'app.intervention.forms.fields.users.label'
            ])
            ->add('detail',FroalaEditorType::class,array(
                'required' => false,
                'label' => 'app.intervention.forms.fields.detail',
                'pluginsEnabled' => ['lists']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InterventionInterne::class,
        ]);
    }
}
