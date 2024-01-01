<?php

namespace App\Form;

use App\Entity\Food;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
// use Symfony\Component\Form\Extension\Core\Type\NumberType;

class FoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'step' => 'any', // Allow decimal numbers
                    'pattern' => '\d*', // Allow only digits
                ],
            ])
            ->add('is_available')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de la nourriture',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false
            ])
            ->add('menuItem',  null, [
                'label' => 'Categorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Food::class,
        ]);
    }
}
