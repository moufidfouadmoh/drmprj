<?php

namespace App\Form\Type;

use App\Entity\Classement;
use App\Utils\Type\Choice\GroupeChoiceType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'app.classement.forms.fields.date'
            ])
            ->add('groupe',ChoiceType::class,array(
                'choices' => GroupeChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.classement.forms.fields.groupe.label'
            ))
            ->add('niveau',IntegerType::class,[
                'label' => 'app.classement.forms.fields.niveau.label'
            ])
            ->add('echelon',IntegerType::class,[
                'label' => 'app.classement.forms.fields.echelon.label'
            ])
            ->add('detail',FroalaEditorType::class,array(
                'required' => false,
                'label' => 'app.classement.forms.fields.detail',
                'pluginsEnabled' => ['file','lists'],
                'fileAllowedTypes' => ['application/pdf','application/msword','jpeg', 'jpg','png']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classement::class,
        ]);
    }
}
