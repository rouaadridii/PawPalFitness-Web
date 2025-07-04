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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AnimalsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('age', null, [
                'constraints' => [
                    new Type(['type' => 'integer', 'message' => 'Âge doit être un nombre entier']),
                    new Range(['min' => 1, 'minMessage' => 'Âge doit être supérieur à 0']),
                ],
            ])
            ->add('type')
            ->add('details', TextareaType::class)
            ->add('poids', null, [
                'constraints' => [
                    new Type(['type' => 'float', 'message' => 'Le poids doit être un nombre décimal']),
                    new Range(['min' => 0.1, 'minMessage' => 'Le poids doit être supérieur à 0']),
                ],
            ])
            ->add('idc', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nomc'
            ])
            ->add('idu', EntityType::class, [
                'class' => Personne::class,
                'choice_label' => 'nom'
            ])
            ->add('Add', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
