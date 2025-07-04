<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],  // Bootstrap form-control class
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 255]),
                ],
                'label' => 'Titre du produit',  // Added a French label for consistency
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control'],  // Bootstrap form-control class
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 255]),
                ],
                'label' => 'Description du produit',  // Added a French label for consistency
            ])
            ->add('reference', TextType::class, [
                'attr' => ['class' => 'form-control'],  // Bootstrap form-control class
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 255]),
                ],
                'label' => 'Reference du produit',  // Added a French label for consistency
            ])
            ->add('image', FileType::class, [
                'label' => 'Chargez ici une photo',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control-file'],  // Bootstrap file input class
                'label_attr' => ['class' => 'form-label mt-3'],  // Added margin-top for spacing
            ])
            ->add('prix', NumberType::class, [
                'attr' => ['class' => 'form-control'],  // Bootstrap form-control class
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Le prix doit être supérieur ou égal à 0.'
                    ])
                ],
                'label' => 'Prix',
            ])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Protéines' => 'proteines',
                    'Vitamines' => 'vitamines',
                    'Matériels' => 'materiels',
                ],
                'attr' => ['class' => 'form-select'],  // Bootstrap select class
                'label' => 'Catégorie',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
