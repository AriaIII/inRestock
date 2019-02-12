<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom* :',
                'attr' => [
                    'class' => 'input is-rounded'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La valeur ne peut pas être vide'
                    ]),
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
                'choice_label' => 'societyName',
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
