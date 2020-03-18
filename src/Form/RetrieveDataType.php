<?php

namespace App\Form;

use App\Entity\Brands;
use App\Entity\Categories;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RetrieveDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $website = $options['website'];
        $builder
            ->add('brand',EntityType::class, [
                'class' => Brands::class,
                'placeholder' => 'Choose a brand',
                'query_builder' => function (EntityRepository $er) use ($website) {
                    return $er->createQueryBuilder('b')
                        ->where('b.website = :website')
                        ->setParameter('website',$website);
                },
            ])
            ->add('category',EntityType::class, [
                'class' => Categories::class,
                'placeholder' => 'Choose a category',
                'query_builder' => function (EntityRepository $er) use ($website) {
                    return $er->createQueryBuilder('c')
                        ->where('c.website = :website')
                        ->setParameter('website',$website);
                },
            ])
            ->add('retrieve', SubmitType::class, ['label' => 'Retrieve Data'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'website' => null
            // Configure your form options here
        ]);
    }
}
