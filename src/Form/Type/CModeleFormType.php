<?php

namespace App\Form\Type;

use App\Entity\CModele;
use App\Entity\Statut;
use App\Utils\Type\Choice\DelaiChoiceType;
use App\Utils\Type\Choice\EtatChoiceType;
use App\Utils\Type\Choice\ModeleChoiceType;
use App\Utils\Type\Choice\RequestedChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CModeleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'app.cmodele.forms.fields.nom'
            ])
            ->add('etat',ChoiceType::class,[
                'choices' => EtatChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.cmodele.forms.fields.etat'
            ])
            ->add('type',ChoiceType::class,[
                'choices' => ModeleChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.cmodele.forms.fields.type'
            ])
            ->add('justificatif',ChoiceType::class,[
                'choices' => RequestedChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.cmodele.forms.fields.justificatif'
            ])
            ->add('service',ChoiceType::class,[
                'choices' => RequestedChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.cmodele.forms.fields.service'
            ])
            ->add('departement',ChoiceType::class,[
                'choices' => RequestedChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.cmodele.forms.fields.departement'
            ])
            ->add('direction',ChoiceType::class,[
                'choices' => RequestedChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.cmodele.forms.fields.direction'
            ])
            ->add('fixe',ChoiceType::class,[
                'choices' => DelaiChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'required' => false,
                'label' => 'app.cmodele.forms.fields.fixe'
            ])
            ->add('delaimin',DateIntervalType::class,[
                'required' => false,
                //'widget'      => 'integer',
                'with_years'  => true,
                'with_months' => true,
                'with_days'   => true,
                'with_hours'   => true,
                'label' => 'app.cmodele.forms.fields.delai.min.label',
                'labels' => array(
                    'years' => 'app.cmodele.forms.fields.delai.labels.years',
                    'months' => 'app.cmodele.forms.fields.delai.labels.months',
                    'days' => 'app.cmodele.forms.fields.delai.labels.days',
                    'hours' => 'app.cmodele.forms.fields.delai.labels.hours'
                )
            ])
            ->add('delaimax',DateIntervalType::class,[
                'required' => false,
                //'widget'      => 'integer',
                'with_years'  => true,
                'with_months' => true,
                'with_days'   => true,
                'with_hours'   => true,
                'label' => 'app.cmodele.forms.fields.delai.max.label',
                'labels' => array(
                    'years' => 'app.cmodele.forms.fields.delai.labels.years',
                    'months' => 'app.cmodele.forms.fields.delai.labels.months',
                    'days' => 'app.cmodele.forms.fields.delai.labels.days',
                    'hours' => 'app.cmodele.forms.fields.delai.labels.hours'
                )
            ])
            ->add('statuts',EntityType::class,[
                'class' => Statut::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'label' => 'app.cmodele.forms.fields.statuts.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CModele::class,
        ]);
    }
}
