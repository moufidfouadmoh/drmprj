<?php

namespace App\Form\Type;

use App\Entity\Agence;
use App\Entity\Lieu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgenceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'app.agence.forms.fields.nom'
            ])
            ->add('lieu',EntityType::class,[
                'by_reference' => true,
                'multiple' => false,
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'app.agence.forms.fields.lieu.label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agence::class
        ]);
    }
}
