<?php

namespace App\Form\Type;

use App\Entity\Formation;
use App\Utils\Type\Choice\MentionChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'app.user.forms.fields.formations.date'
            ])
            ->add('mention',ChoiceType::class,[
                'choices' => MentionChoiceType::getChoices(),
                'multiple'  => false,
                'label' => 'app.user.forms.fields.formations.mention'
            ])
            ->add('cours', CoursFormType::class,[
                'label' => 'app.user.forms.fields.formations.cours',
            ])
            ->add('adresse',AdresseFormType::class,[
                'label' => false
            ])
            ->add('diplome',DiplomeFormType::class,[
                'label' => 'app.user.forms.fields.formations.diplome',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }


}
