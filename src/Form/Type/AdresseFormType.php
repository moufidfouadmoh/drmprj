<?php

namespace App\Form\Type;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ville',TextType::class,[
                'label' => 'app.user.forms.fields.formations.ville'
            ])
            ->add('postale',TextType::class,[
                'label' => 'app.user.forms.fields.formations.adresse'
            ])
            ->add('email',EmailType::class,[
                'label' => 'app.user.forms.fields.formations.email'
            ])
            ->add('telephone',TextType::class,[
                'label' => 'app.user.forms.fields.formations.tel'
            ])
            ->add('pays',PaysFormType::class)
            ->add('etablissement',EtablissementFormType::class,[
                'label' => 'app.user.forms.fields.formations.etablissement',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
