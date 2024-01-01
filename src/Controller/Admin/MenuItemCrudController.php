<?php

namespace App\Controller\Admin;

use App\Entity\Food;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class MenuItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Food::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextEditorField::new('description'),
            TextField::new('price'),
            AssociationField::new('menuItem')
                ->setLabel('Categorie'),
            ImageField::new('imageFile')
                ->setLabel('Food image')
                ->setUploadDir('public/images/foods')
                ->setBasePath('images/foods'),
            BooleanField::new('is_available')
                ->setLabel('Availability'),
        ];
    }
    
}
