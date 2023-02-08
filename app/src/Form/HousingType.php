<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Housing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('rooms')
            ->add('beds')
            ->add('availability_start', null, [
                'widget' => 'single_text'
            ])
            ->add('availability_end', null, [
                'widget' => 'single_text'
            ])
            ->add('city')
            ->add('region')
            ->add('country')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('is_published')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Housing::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
