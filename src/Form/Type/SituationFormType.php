<?php

namespace App\Form\Type;

use App\Entity\Situation;
use App\Entity\Statut;
use App\Utils\Type\Choice\SituationChoiceType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SituationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'app.situation.forms.fields.date'
            ])
            ->add('reference',TextType::class,[
                'label' => 'app.situation.forms.fields.reference'
            ])
            ->add('type',ChoiceType::class,[
                'expanded' => true,
                'multiple' => false,
                'choices' => SituationChoiceType::getChoices(),
                'label' => 'app.situation.forms.fields.type'

            ])
            ->add('statut',EntityType::class,[
                'class' => Statut::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => 'app.situation.forms.fields.statut.label'
            ])
            ->add('detail',FroalaEditorType::class,array(
                'required' => false,
                'label' => 'app.situation.forms.fields.detail',
                'pluginsEnabled' => ['file','lists'],
                'fileAllowedTypes' => ['application/pdf','application/msword','jpeg', 'jpg','png']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Situation::class,
        ]);
    }
}
