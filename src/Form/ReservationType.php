<?php
namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('places', IntegerType::class, [
                'label' => 'Places',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter number of places'],
            ])
            ->add('category', TextType::class, [
                'label' => 'Category',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter category'],
            ])
            ->add('date', TextType::class, [
                'label' => 'Date',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter date'],
            ])
            ->add('startTime', TextType::class, [
                'label' => 'Start Time',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter start time'],
            ])
            ->add('endTime', TextType::class, [
                'label' => 'End Time',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter end time'],
            ])
            ->add('status', TextType::class, [
                'label' => 'Status',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter status'],
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duration',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter duration'],
            ])
            ->add('pricing', IntegerType::class, [
                'label' => 'Pricing',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter pricing'],
            ]);

        // Add the Add or Update button based on form context
        if ($options['is_edit']) {
            // Add the Update button for edit context
            $builder->add('Update', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            ]);
        } else {
            // Add the Add button for add context
            $builder->add('Add', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'is_edit' => false, // Add option to determine form context (add or edit)
        ]);
    }
}