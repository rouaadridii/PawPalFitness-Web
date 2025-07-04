<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Salle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AbonnementTypeFront extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('duree_abonnement', ChoiceType::class, [
                'choices' => [
                    '1 month' => '1',
                    '3 months' => '3',
                    '6 months' => '6',
                ],
                'attr' => ['class' => 'form-select'],
                'label' => 'Duration',
            ])
            ->add('prix_abonnement', ChoiceType::class, [
                'choices' => [
                    '100dt' => '100',
                    '300dt' => '300',
                    '600dt' => '600',
                ],
                'attr' => ['class' => 'form-select'],
                'label' => 'Price',
            ])
            
            ->add('date_deb_abonnement') // Consider using DateType
            ->add('date_fin_abonnement'); // Consider using DateType

        // Conditionally add Salle only if not preset
        if (!$options['salle']) {
            $builder->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nom_salle',
                'attr' => ['class' => 'form-select'],
                'label' => 'Salle',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
            'salle' => null,  // Add a default null option for salle
        ]);
    }
}
