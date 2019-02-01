<?php

namespace App\Form;

use App\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Product;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('societyName', TextType::class, [
                'label' => 'Nom de la société :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom et prénom* :',
                'attr' => [
                    'class' => 'input is-rounded'
                ],
                'required' => 'true'
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email :',
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
            ->add('adress', TextareaType::class, [
                'label' => 'Adresse :',
                'attr' => [
                    'class' => 'textarea'
                    ]
            ])
            ->add('postcode', TextType::class, [
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
            ->add('products', EntityType::class, [
                'label' => 'Rôle* :',
                'class' => Product::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Supplier::class,
        ]);
    }
}
