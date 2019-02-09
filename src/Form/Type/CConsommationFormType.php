<?php

namespace App\Form\Type;

use App\Entity\CConsommation;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\CongeFormBuilder;
use App\Utils\Type\Choice\YearChoiceType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CConsommationFormType extends AbstractType
{
    use CongeFormBuilder;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['check']){
            $builder
                ->add('user',EntityType::class,[
                    'required' => false,
                    'class' => User::class,
                    'query_builder' => function(UserRepository $repository){
                        $qb = $repository->selectAllWithSituation();
                        return $qb;
                    },
                    'choice_label' => function(User $user){
                        return $user->getNomPrenom();
                    }
                ])
            ;
        }

        if($options['create']){
            $builder
                ->add('datedebut',DateType::class,[
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'label' => 'app.cconsommation.forms.fields.datedebut'
                ])
            ;
            if($options['annee']){
                $builder
                    ->add('annee',ChoiceType::class,[
                        'choices' => YearChoiceType::getChoices(2017),
                        'label' => 'app.cconsommation.forms.fields.annee'
                    ]);
            }
            if($options['delaimin'] || $options['delaimax']){
                $extras = [
                    //'widget'      => 'integer',
                    'with_years' => $options['Nminyears'] > 0 || $options['Nmaxyears'] > 0,
                    'with_months' => $options['Nminmonths'] > 0 || $options['Nmaxmonths'] > 0,
                    'with_days' => $options['Nmindays'] > 0 || $options['Nmaxdays'] > 0,
                    'labels' => array(
                        'years' => 'app.cmodele.forms.fields.delai.labels.years',
                        'months' => 'app.cmodele.forms.fields.delai.labels.months',
                        'days' => 'app.cmodele.forms.fields.delai.labels.days'
                    ),
                    'label' => 'app.cconsommation.forms.fields.delai.accorde'
                ];
                if($options['Nmaxyears']>0){
                    $extras = array_merge($extras,['days' => $this->range($options['Nminyears']>0 ? $options['Nminyears'] : 1,$options['Nmaxyears'])]);
                }
                if($options['Nmaxmonths']>0){
                    $extras = array_merge($extras,['days' => $this->range($options['Nminmonths']>0 ? $options['Nminmonths'] : 1,$options['Nmaxmonths'])]);
                }
                if($options['Nmaxdays']>0){
                    $extras = array_merge($extras,['days' => $this->range($options['Nmindays']>0 ? $options['Nmindays'] : 1,$options['Nmaxdays'])]);
                }
                $builder
                    ->add('delaiaccorde',DateIntervalType::class,$extras)
                ;
            }

            if($options['motif']){
                $builder
                    ->add('motif',FroalaEditorType::class,array(
                        //'required' => false,
                        'label' => 'app.cconsommation.forms.fields.motif',
                        'pluginsEnabled' => ['file','lists','fullscreen'],
                        'fileAllowedTypes' => ['application/pdf', 'application/msword','jpeg', 'jpg', 'png']
                    ))
                ;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CConsommation::class,
            'delaimin' => false,
            'delaimax' => false,
            'motif' => false,
            'annee' => false,
            'check' => false,
            'create' => false,
            'accord' => false,
            'Nminyears' => 0,
            'Nminmonths' => 0,
            'Nmindays' => 0,
            'Nmaxyears' => 0,
            'Nmaxmonths' => 0,
            'Nmaxdays' => 0,
        ]);

        $resolver->setAllowedTypes('delaimin','boolean');
        $resolver->setAllowedTypes('delaimax','boolean');
        $resolver->setAllowedTypes('motif','boolean');
        $resolver->setAllowedTypes('annee','boolean');
        $resolver->setAllowedTypes('check','boolean');
        $resolver->setAllowedTypes('create','boolean');
        $resolver->setAllowedTypes('accord','boolean');
        $resolver->setAllowedTypes('Nminyears','integer');
        $resolver->setAllowedTypes('Nminmonths','integer');
        $resolver->setAllowedTypes('Nmindays','integer');
        $resolver->setAllowedTypes('Nmaxyears','integer');
        $resolver->setAllowedTypes('Nmaxmonths','integer');
        $resolver->setAllowedTypes('Nmaxdays','integer');
    }

    private function range($min,$max)
    {
        $range = [];
        for($i = $min; $i<=$max;$i++){
            $range[$i] = $i;
        }
        return $range;
    }
}
