<?php

namespace App\Form\Type;

use App\Entity\MaterielMobilier;
use App\Utils\Type\Choice\UniteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielMobilierFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipement',EquipementFormType::class,[
                'label' => 'app.materiel.forms.fields.equipement',
            ])
            ->add('modele',TextType::class,[
                'required' => false,
                'label' => 'app.materiel.forms.fields.modele'
            ])
            ->add('unite',ChoiceType::class,[
                'choices' => UniteChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.materiel.forms.fields.unite',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MaterielMobilier::class,
        ]);
    }
}
