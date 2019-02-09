<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Utils\Type\Choice\RoleChoiceType;
use App\Utils\Type\Choice\SexeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserFormType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->authorizationChecker->isGranted(User::ROLE_SUPER_ADMIN)){
            $builder
                ->add('enabled',CheckboxType::class,[
                    'label' => 'app.user.forms.fields.enabled'
                ])
                ->add('roles',ChoiceType::class,[
                    'choices' => RoleChoiceType::getChoices(),
                    'expanded' => true,
                    'multiple'  => true,
                    'label' => 'app.user.forms.fields.roles'
                ])
            ;
        }
        $builder
            ->add('username',TextType::class,[
                'label' => 'app.user.forms.fields.username'
            ])
            ->add('nom',TextType::class,[
                'label' => 'app.user.forms.fields.nom'
            ])
            ->add('prenom',TextType::class,[
                'label' => 'app.user.forms.fields.prenom'
            ])
            ->add('adressepostale',TextType::class,[
                'required' => false,
                'label' => 'app.user.forms.fields.adressepostale'
            ])
            ->add('lieunaissance',TextType::class,[
                'required' => false,
                'label' => 'app.user.forms.fields.lieunaissance'
            ])
            ->add('telephone1',TextType::class,[
                'label' => 'app.user.forms.fields.telephone1'
            ])
            ->add('telephone2',TextType::class,[
                'required' => false,
                'label' => 'app.user.forms.fields.telephone2'
            ])
            ->add('telephone3',TextType::class,[
                'required' => false,
                'label' => 'app.user.forms.fields.telephone3'
            ])
            ->add('email',EmailType::class,[
                'label' => 'app.user.forms.fields.email'
            ])
            ->add('datenaissance',DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'app.user.forms.fields.datenaissance'
            ])
            ->add('sexe',ChoiceType::class,array(
                'choices' => SexeChoiceType::getChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'app.user.forms.fields.sexe'
            ))
            ->add('formations',CollectionType::class,[
                'entry_type' => FormationFormType::class,
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
                    'class' => 'table formation-collection'
                ),
                'label' => false
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
