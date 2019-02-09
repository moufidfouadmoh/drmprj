<?php

namespace App\Form\Type;

use App\Form\Transformer\EtablissementFormTransformer;
use App\Manager\EtablissementManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtablissementFormType extends AbstractType
{
    /**
     * @var EtablissementManagerInterface
     */
    private $manager;

    public function __construct(EtablissementManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new EtablissementFormTransformer($this->manager),true)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Etablissement::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }
}
