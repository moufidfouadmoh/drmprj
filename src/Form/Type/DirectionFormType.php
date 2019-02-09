<?php

namespace App\Form\Type;

use App\Entity\Agence;
use App\Entity\Departement;
use App\Entity\Direction;
use App\Entity\Service;
use App\Utils\Type\Choice\DirectionChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DirectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'app.direction.forms.fields.nom'
            ])
            ->add('telephone',TextType::class,[
                'label' => 'app.direction.forms.fields.telephone'
            ])
            ->add('departements',EntityType::class,[
                'required' => false,
                'by_reference' => false,
                'multiple' => true,
                'class' => Departement::class,
                'choice_label' => 'nom',
                'label' => 'app.direction.forms.fields.departements'
            ])
            ->add('services',EntityType::class,[
                'required' => false,
                'by_reference' => false,
                'multiple' => true,
                'class' => Service::class,
                'choice_label' => 'nom'
            ])
            ->add('direction',EntityType::class,[
                'required' => false,
                'class' => Direction::class,
                'choice_label' => 'nom',
                'label' => 'app.direction.forms.fields.departements'
            ])
            ->add('concern',ChoiceType::class,[
                'choices' => DirectionChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false
            ])
            ->add('agences',EntityType::class,[
                'by_reference' => true,
                'multiple' => true,
                'class' => Agence::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Direction::class,
        ]);
    }
}
