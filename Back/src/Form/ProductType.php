<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Supplier;
use App\Entity\Category;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom* :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie* :',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                // 'expanded' => true,
                'required' => true,
            ])

            ->add('suppliers', EntityType::class, [
                'label' => 'Fournisseurs :',
                'class' => Supplier::class,
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
            'data_class' => Product::class,
        ]);
    }
}
