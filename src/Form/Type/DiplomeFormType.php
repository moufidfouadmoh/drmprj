<?php

namespace App\Form\Type;

use App\Form\Transformer\DiplomeFormTransformer;
use App\Manager\DiplomeManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiplomeFormType extends AbstractType
{
    /**
     * @var DiplomeManagerInterface
     */
    private $manager;

    public function __construct(DiplomeManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new DiplomeFormTransformer($this->manager),true)
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Diplome::class,
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
