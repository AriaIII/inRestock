<?php

namespace App\Form;

use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stock', TextType::class, [
                'label' => 'Valeur actuelle du stock :',
                'attr' => [
                    'class' => 'input is-rounded'
                    ]
                
            ])
            ->add('stock_alert', TextType::class, [
                'label' => 'Limite basse du stock (déclenche une alerte quand elle est atteinte)* :',
                'attr' => [
                    'class' => 'input is-rounded'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La valeur ne peut pas être vide'
                    ]),
                ]
            ])
            ->add('packaging', TextType::class, [
                'label' => 'Conditionnement du produit* :',
                'attr' => [
                    'class' => 'input is-rounded'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La valeur ne peut pas être vide'
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
