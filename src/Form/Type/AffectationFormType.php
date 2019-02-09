<?php

namespace App\Form\Type;

use App\Entity\Affectation;
use App\Entity\Agence;
use App\Entity\Bureau;
use App\Entity\Fonction;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('fonctionOrigin',EntityType::class,[
                'class' => Fonction::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'app.affectation.forms.fields.fonction.origin'
            ])*/
            ->add('fonctionDest',EntityType::class,[
                'class' => Fonction::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'app.affectation.forms.fields.fonction.dest'
            ])
            /*->add('bureauOrigin',EntityType::class,[
                'class' => Bureau::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'app.affectation.forms.fields.bureau.origin'
            ])*/
            ->add('bureauDest',EntityType::class,[
                'class' => Bureau::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'app.affectation.forms.fields.bureau.dest'
            ])
            /*->add('agenceOrigin',EntityType::class,[
                'class' => Agence::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'app.affectation.forms.fields.agence.origin'
            ])*/
            ->add('agenceDest',EntityType::class,[
                'class' => Agence::class,
                'choice_label' => 'nom',
                'label' => 'app.affectation.forms.fields.agence.dest'
            ])
            ->add('date',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'app.affectation.forms.fields.date'
            ])
            ->add('reference',TextType::class,[
                'required' => false,
                'label' => 'app.affectation.forms.fields.reference'
            ])
            ->add('detail',FroalaEditorType::class,array(
                'required' => false,
                'label' => 'app.affectation.forms.fields.detail',
                'pluginsEnabled' => ['file','lists'],
                'fileAllowedTypes' => ['application/pdf','application/msword','jpeg', 'jpg','png']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class
        ]);
    }
}
