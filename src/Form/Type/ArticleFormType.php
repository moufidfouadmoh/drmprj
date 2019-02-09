<?php

namespace App\Form\Type;

use App\Entity\Article;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'app.article.forms.fields.title'
            ])
            ->add('content',FroalaEditorType::class,array(
                'label' => 'app.article.forms.fields.content',
                'pluginsEnabled' => ['lists','table','align'] //,'paragraph_format','paragraph_style'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
