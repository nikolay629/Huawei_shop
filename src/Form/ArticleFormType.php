<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticleTranslation;
use App\Entity\Language;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr'=> [
                    'class' => 'form-control',
                    'placeholder' => 'Insert name for article ...',
                ]
            ])
            ->add('small_description',  TextareaType::class,[
                'label' => 'Small Description',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Insert small description for article ...',
                    'style' => 'height: 150px'
                ]
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Insert description for article ...',
                    'style' => 'height: 150px'
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
            ->add("submit", SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleTranslation::class
        ]);
    }
}
