<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Role;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('username', TextType::class, [
                'label' => 'pseudo :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('adress', TextareaType::class, [
                'label' => 'Adresse :',
                'attr' => [
                    'class' => 'textarea'
                    ]
            ])
            ->add('postcode',  TextType::class, [
                'label' => 'Code postal :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('town', TextType::class, [
                'label' => 'Ville :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo :',
                'attr' => [
                    'class' => 'file'
                ]
            ])
            ->add('role', EntityType::class, [
                'label' => 'Rôle :',
                'class' => Role::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('password', RepeatedType::class, [
                'empty_data' => '',
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot passe doivent correspondre.',
                'options' => [
                    'attr' => [
                        'class' => 'input is-rounded'
                    ]
                ],
                'first_options'  => [
                    'label' => 'Mot de passe :',
                ],
                'second_options' => [
                    'label' => 'Verification du mot de passe :'
                ],
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
