<?php

namespace App\Form\Type;

use App\Entity\Agence;
use App\Entity\Departement;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'app.service.forms.fields.nom'
            ])
            ->add('telephone',TextType::class,[
                'label' => 'app.service.forms.fields.telephone'
            ])
            ->add('agences',EntityType::class,[
                'by_reference' => true,
                'multiple' => true,
                'class' => Agence::class,
                'choice_label' => 'nom',
                'label' => 'app.service.forms.fields.agences.label'
            ])
            ->add('departement',EntityType::class,[
                'required' => false,
                'by_reference' => true,
                'multiple' => false,
                'class' => Departement::class,
                'choice_label' => 'nom',
                'label' => 'app.service.forms.fields.departement.label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
