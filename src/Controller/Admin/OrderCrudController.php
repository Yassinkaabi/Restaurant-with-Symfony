<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('Date_Placed')
                ->setFormat('dd-MM-yyyy HH:mm')
                ->setLabel('Date Placed'),
            TextField::new('status'),
            AssociationField::new('user'),
        ];
    }

    // public function configureCrud(Crud $crud): Crud
    // {
    //     return $crud
    //         ->addFormTheme('path/to/your/form/theme.html.twig');
    // }

    // public function configureResponseParameters(Response $response): void
    // {
    //     if ($response->isSuccessful() && 'index' === $response->getContent()) {
    //         $orderRepository = $this->getDoctrine()->getRepository(Order::class);
    //         $totalAmount = $orderRepository->getTotalAmount();

    //         $this->addFlash('success', 'Total Amount of All Orders: ' . $totalAmount);
    //     }
    // }
    
}
