<?php

namespace App\Form\Type;

use App\Entity\Fonction;
use App\Utils\Type\Choice\EtatChoiceType;
use App\Utils\Type\Choice\FonctionChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FonctionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'app.fonction.forms.fields.nom'
            ])
            ->add('etat',ChoiceType::class,[
                'choices' => EtatChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false
            ])
            ->add('type',ChoiceType::class,[
                'choices' => FonctionChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fonction::class,
        ]);
    }
}
