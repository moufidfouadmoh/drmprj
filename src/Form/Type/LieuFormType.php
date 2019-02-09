<?php

namespace App\Form\Type;

use App\Entity\Lieu;
use App\Utils\Type\Choice\IleChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'app.lieu.forms.fields.nom'
            ])
            ->add('ile',ChoiceType::class,[
                'choices' => IleChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.lieu.forms.fields.ile'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
