<?php

namespace App\Form;

use App\Entity\Travailleur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TravailleurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Add fields specific to Travailleur entity
            ->add('diplome')
            ->add('experience')
            ->add('langue')
            ->add('categorie')
            ->add('personne', PersonneType::class, [
                'role_default_value' => $options['role_default_value'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Travailleur::class,
            'role_default_value' => 3,
        ]);
    }
}
