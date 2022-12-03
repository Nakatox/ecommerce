<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Form\Model\ProductSearchModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends PaginationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name', TextType::class)
            ->add('hasQuantity', ChoiceType::class)
            ->add('priceMin', IntegerType::class)
            ->add('priceMax', IntegerType::class)
            ->add('category', EntityType::class,
                [
                    'class' => Category::class,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductSearchModel::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
