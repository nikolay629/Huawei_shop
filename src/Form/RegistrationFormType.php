<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'style' => 'background-color: #99c2ff'
                ]
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'style' => 'background-color: #99c2ff'
                ]
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'style' => 'background-color: #99c2ff'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'style' => 'background-color: #99c2ff'
                ]
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'first_options'=>[
                    'label'=>'Password',
                    'attr' => [
                        'style' => 'background-color: #99c2ff'
                    ]
                ],
                'second_options'=>[
                    'label' => 'Confirm Password',
                    'attr' => [
                        'style' => 'background-color: #99c2ff'
                    ]
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ])
                ],

            ])
            ->add('roles', ChoiceType::class,[
                //'mapped' => false,
                'multiple' => true,
                'expanded'=> true ,
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN'
                ],
                'choice_attr' => [
                    'User' => ['style' => 'margin: 5px 5px 5px 5px'],
                    'Admin' => ['style' => 'margin: 5px 5px 5px 5px']
                ]
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
