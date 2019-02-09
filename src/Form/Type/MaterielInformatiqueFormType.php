<?php

namespace App\Form\Type;

use App\Entity\MaterielInformatique;
use App\Utils\Type\Choice\UniteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielInformatiqueFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipement',EquipementFormType::class,[
                'label' => 'app.materiel.forms.fields.equipement',
            ])
            ->add('marque',MarqueFormType::class,[
                'label' => 'app.materiel.forms.fields.marque',
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
            'data_class' => MaterielInformatique::class
        ]);
    }
}
