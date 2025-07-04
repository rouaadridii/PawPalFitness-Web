<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('status', ChoiceType::class, [
            'choices' => [
                'Pending' => 'pending',
                'Processing' => 'processing',
                'Completed' => 'completed',
                'Cancelled' => 'cancelled',
            ],
            'attr' => ['class' => 'form-select'],
            'label' => 'Status',
            'label_attr' => ['class' => 'form-label mt-4'],
        ])
        ->add('createdAt', DateTimeType::class, [
            'widget' => 'single_text', // Use HTML5 input[type="datetime-local"]
            'attr' => ['class' => 'form-control'],
            'label' => 'Created At',
            'label_attr' => ['class' => 'form-label mt-4'],
            // If you want these fields to be non-editable, consider adding 'disabled' => true in the attr array
        ])
        ->add('updatedAt', DateTimeType::class, [
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control'],
            'label' => 'Updated At',
            'label_attr' => ['class' => 'form-label mt-4'],
            // Similarly, you might disable this or handle it automatically on the backend
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
