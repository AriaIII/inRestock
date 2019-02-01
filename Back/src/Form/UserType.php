<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // je construis un formulaire en fonction de la page new ou edit : le mot de passe va être géré différemment si c'est un nouvel utilisateur ou si c'est un salarié déjà existant.
        // On ne va pas être obligé de resaisir le mot de passe si le salarié existe déjà, on le précise à l'utilisateur
        $listener = function (FormEvent $event) {

            //je récupère l'objet que j'essaye de setter sur mon form + le formulaire actuel en cours de construction
            $user = $event->getData();
            $currentForm = $event->getForm();
            
        
        /*
         $currentForm est le formulaire en cours ce qui fait que comme un formulaire normal je peux utiliser les methodes add() comme précédemment.
         De ce fait je peux conditionner l'ajout ou la suppression de champs potentiels dans cette méthode
         Si mon objet a un id null, c'est que l'objet n'est pas encore créé en BDD = NEW et si mon objet a un id, c'est qu'il existe en BDD = EDIT
        */
        if(is_null($user->getId())){
            $currentForm
            ->add('username', TextType::class, [
                'label' => 'Pseudo :',
                'attr' => [
                    'class' => 'input is-rounded',
                    'readonly' => true,
                    'placeholder' => 'Ce champ est rempli automatiquement.'
                ],
                
            ])
            ->add('password', RepeatedType::class, [
                'empty_data' => '',
                'required' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux champs du mot passe doivent être identiques.',
                'options' => [
                    'attr' => [
                        'class' => 'input is-rounded'
                    ]
                ],
                'first_options'  => [
                    'label' => 'Mot de passe* :',
                ],
                'second_options' => [
                    'label' => 'Vérification du mot de passe* :'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La valeur ne peut pas être vide'
                    ]),
                    new Length([
                        'min'        => 4,
                        'max'        => 6,
                        'minMessage' => 'Votre mot de passe doit avoir un min de {{ limit }} caractères.',
                        'maxMessage' => 'Votre mot de passe doit avoir un max de {{ limit }} caractères.'
                    ])
                ]
            ])
            
            ;
        } else {
            $currentForm
            ->add('username', TextType::class, [
                'label' => 'Pseudo : ce champ ne peut pas être modifié',
                'attr' => [
                    'class' => 'input is-rounded',
                    'readonly' => true,
                    'placeholder' => 'Ce champ est rempli automatiquement.'
                ],
                
            ])
            ->add('password', RepeatedType::class, [
                'empty_data' => '',
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux champs du mot passe doivent être identiques.',
                'options' => [
                    'attr' => [
                        'class' => 'input is-rounded',
                        'placeholder' => 'Ne remplir que si vous voulez changer le mot passe.'
                    ]
                ],
                'first_options'  => [
                    'label' => 'Mot de passe :',
                ],
                'second_options' => [
                    'label' => 'Vérification du mot de passe :'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La valeur ne peut pas être vide'
                    ]),
                    new Length([
                        'min'        => 4,
                        'max'        => 6,
                        'minMessage' => 'Votre mot de passe doit avoir un min de {{ limit }} caractères.',
                        'maxMessage' => 'Votre mot de passe doit avoir un max de {{ limit }} caractères.'
                    ])
                ]
            ])
            
            ;
        }          
    };
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom* :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille* :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, $listener)
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
                'label' => 'Photo (format gif, png, jpeg) :',
                'attr' => [
                    'class' => 'file'
                ],
                'required' => false,
            ])
            ->add('role', EntityType::class, [
                'label' => 'Rôle* :',
                'class' => Role::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'required' => true,
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
