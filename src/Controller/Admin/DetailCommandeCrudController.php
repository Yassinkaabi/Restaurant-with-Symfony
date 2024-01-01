<?php

namespace App\Controller\Admin;

use App\Entity\DetailCommande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class DetailCommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DetailCommande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IdField::new('price'),
            IntegerField::new('quantity'),            
            AssociationField::new('order_id'),
            AssociationField::new('food'),
        ];
    }
    
}
