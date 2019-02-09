<?php

namespace App\Form\Type;

use App\Entity\Cadrage;
use App\Entity\Categorie;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CadrageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'app.cadrage.forms.fields.date'
            ])
            ->add('categorie',EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'app.cadrage.forms.fields.categorie.label'
            ])
            ->add('detail',FroalaEditorType::class,array(
                'required' => false,
                'label' => 'app.cadrage.forms.fields.detail',
                'pluginsEnabled' => ['file','lists'],
                'fileAllowedTypes' => ['application/pdf','application/msword','jpeg', 'jpg','png']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cadrage::class,
        ]);
    }
}
