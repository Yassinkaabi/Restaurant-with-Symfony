<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [    
            IdField::new('id')->hideOnForm(),
            TextField::new('content'),
            AssociationField::new('user'), // Assuming there's a 'user' property in the Reservation entity
            AssociationField::new('restaurant'), // Assuming there's a 'restaurant' property in the Reservation entity
            AssociationField::new('table'), // Assuming there's a 'restaurant' property in the Reservation entity
            BooleanField::new('is_verified'),
        ];
    }
}
