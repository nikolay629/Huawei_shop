<?php

namespace App\Form;

use App\Entity\CommentTranslation;
use App\Entity\Language;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentTransFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentTrans',TextType::class, [
                'label' => 'Comment',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Insert comment ...'
                ]
            ])
            ->add('language', EntityType::class, array(
                'label' => 'Language',
                'class' => Language::class,
                'choice_label' => 'language',
                'attr'=> [
                    'class' => 'form-control'
                ]
            ))
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentTranslation::class,
        ]);
    }
}
