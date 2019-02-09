<?php

namespace App\Form\Type;

use App\Entity\PatrimoineInformatique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatrimoineInformatiqueFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'app.inventaire.forms.fields.date'
            ])
            ->add('inventaires',CollectionType::class,[
                'entry_type' => InventaireInformatiqueFormType::class,
                'entry_options' => [
                    'label' => false,
                    'attr' => array(
                        'class' => 'item',
                    )
                ],
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'delete_empty' => true,
                'attr' => array(
                    'class' => 'table inventaire-collection'
                ),
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PatrimoineInformatique::class,
        ]);
    }
}
