<?php

namespace App\Form;

use App\Entity\Language;
use App\Entity\PageTrans;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageTransType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('smallTitle', TextType::class, [
                'label' => 'Small Title',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Insert small title ...'
                ]
            ])
            ->add('description', FroalaEditorType::class)
            ->add('locale', EntityType::class, array(
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
            'data_class' => PageTrans::class,
        ]);
    }
}
