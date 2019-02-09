<?php

namespace App\Form\Type;

use App\Entity\Categorie;
use App\Utils\Type\Choice\EtatChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'app.categorie.forms.fields.nom'
            ])
            ->add('etat',ChoiceType::class,[
                'choices' => EtatChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.categorie.forms.fields.etat'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
