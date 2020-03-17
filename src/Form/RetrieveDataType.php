<?php

namespace App\Form;

use App\Entity\Brands;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RetrieveDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand',EntityType::class, [
                'class' => Brands::class,
                'placeholder' => 'Choose a brand'
            ])
            ->add('category',EntityType::class, [
                'class' => Categories::class,
                'placeholder' => 'Choose a category'
            ])
            ->add('retrieve', SubmitType::class, ['label' => 'Retrieve Data'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
