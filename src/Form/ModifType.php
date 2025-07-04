<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Categorie;
use App\Entity\Personne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;

class ModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('age', null, [
            'constraints' => [
                new NotBlank(['message' => 'Vous devez mettre l age!!!']),
                new Type(['type' => 'integer', 'message' => 'Âge doit être un nombre entier']),
                new Range(['min' => 1, 'minMessage' => 'Âge doit être supérieur à 0']),
            ],
        ])
        ->add('details', TextareaType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Vous devez mettre les details!!!']),
            ],
        ])
        ->add('poids', null, [
            'constraints' => [
                new NotBlank(['message' => 'Vous devez mettre le poids!!!']),
                new Type(['type' => 'float', 'message' => 'Le poids doit être un nombre décimal']),
                new Range(['min' => 0.1, 'minMessage' => 'Le poids doit être supérieur à 0']),
            ],
        ])
            ->add('idc', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nomc',
            ])
            ->add('idu', EntityType::class, [
                'class' => Personne::class,
                'choice_label' => 'nom',
            ])
            ->add('Edit_Profile',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
