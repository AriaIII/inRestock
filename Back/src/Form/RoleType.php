<?php

namespace App\Form;

use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code* :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom* :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
