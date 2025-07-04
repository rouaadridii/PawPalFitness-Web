<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\OrderItem;
use Proxies\__CG__\App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('quantity', IntegerType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Quantity',
            'label_attr' => ['class' => 'form-label mt-4'],
            ])
            ->add('product', EntityType::class, [
            'class' => Product::class,
            'choice_label' => 'name', // Assuming 'name' is a property in the Product entity
            'attr' => ['class' => 'form-select mt-3'],
            'label' => 'Product',
            'label_attr' => ['class' => 'form-label mt-4'],
            ])
            ->add('orderRef', EntityType::class, [
            'class' => Order::class,
            'choice_label' => 'id', // Assuming 'reference' is a property in the Order entity
            'attr' => ['class' => 'form-select mt-3'],
            'label' => 'Order Reference',
            'label_attr' => ['class' => 'form-label mt-4'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class,
        ]);
    }
}
