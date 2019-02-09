<?php

namespace App\Form\Type;

use App\Entity\InterventionExterne;
use App\Entity\User;
use App\Repository\UserRepository;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionExterneFormType extends AbstractType
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
            ->add('equipement',TextType::class,[
                'label' => 'app.intervention.forms.fields.materiels.label'
            ])
            ->add('solution',TextType::class,[
                'label' => 'app.intervention.forms.fields.solution'
            ])
            ->add('lieu',TextType::class,[
                'label' => 'app.intervention.forms.fields.lieu'
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
            'data_class' => InterventionExterne::class,
        ]);
    }
}
