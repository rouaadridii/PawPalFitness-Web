<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => ['class' => 'form-control'], // Bootstrap class
            'label' => 'Product Name',
            'label_attr' => ['class' => 'form-label']
        ])
        ->add('description', TextareaType::class, [
            'attr' => ['class' => 'form-control', 'rows' => 3],
            'label' => 'Product Description',
            'label_attr' => ['class' => 'form-label']
        ])
        ->add('price', MoneyType::class, [
            'currency' => 'TND', 
            'attr' => ['class' => 'form-control'],
            'label' => 'Price',
            'label_attr' => ['class' => 'form-label']
        ])
        ->add('category', ChoiceType::class, [
            'choices' => [
                'Proteins' => 'Proteins',
                'Vitamins' => 'Vitamins',
            ],
            'attr' => ['class' => 'form-select'],
            'label' => 'Niveau de la classe',
        ])
        ->add('image', FileType::class, [
            'label' => 'Chargez ici une photo',
            'required' => false,
            'mapped' => false,
            'attr' => ['class' => 'form-control-file'],
        ])
 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
