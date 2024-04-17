<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RolesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'multiple' => true,
            'expanded' => true,
            'choices' => [
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
                // Add more roles as needed
            ],
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
