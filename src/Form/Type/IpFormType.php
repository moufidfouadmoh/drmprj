<?php

namespace App\Form\Type;

use App\Entity\Agence;
use App\Entity\Bureau;
use App\Entity\Ip;
use App\Repository\AgenceRepository;
use App\Utils\Type\Choice\IleChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IpFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address',TextType::class,[
                'label' => 'app.ip.forms.fields.address'
            ])
            ->add('pc',TextType::class,[
                'required' => false,
                'label' => 'app.ip.forms.fields.pc'
            ])
            ->add('bureau',EntityType::class,[
                'class' => Bureau::class,
                'query_builder' => function(EntityRepository $repository){
                    $qb = $repository->createQueryBuilder('bureau')->from(Bureau::class,'b');
                    $qb
                        ->leftJoin('bureau.agences','agences')
                        ->addSelect('agences')
                        ->leftJoin('agences.lieu','lieu')
                        ->addSelect('lieu')
                        ->andWhere('lieu.ile = :ile')
                        ->setParameter('ile',IleChoiceType::MWALI)
                    ;
                    return $qb;
                },
                'choice_label' => 'nom',
                'label' => 'app.ip.forms.fields.bureau.label'
            ])
            ->add('agence',EntityType::class,[
                'class' => Agence::class,
                'query_builder' => function(AgenceRepository $repository){
                    $qb = $repository->createQueryBuilder('agence');
                    $qb
                        ->leftJoin('agence.lieu','lieu')
                        ->addSelect('lieu')
                        ->andWhere('lieu.ile = :ile')
                        ->setParameter('ile',IleChoiceType::MWALI)
                    ;
                    return $qb;
                },
                'choice_label' => 'nom',
                'label' => 'app.ip.forms.fields.agence.label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ip::class,
        ]);
    }
}
