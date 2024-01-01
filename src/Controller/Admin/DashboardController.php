<?php

namespace App\Controller\Admin;
use App\Entity\User;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Entity\Reservation;
use App\Entity\Categorie;
use App\Entity\Menu;
use App\Entity\Food;
use App\Entity\Order;
use App\Entity\DetailCommande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Controller\Admin\UserCrudController;
use App\Controller\Admin\TableCrudController;
use App\Controller\Admin\RestaurantCrudController;
use App\Controller\Admin\ReservationCrudController;
use App\Controller\Admin\MenuCrudController;
use App\Controller\Admin\MenuItemCrudController;
use App\Controller\Admin\CategorieCrudController;
use App\Controller\Admin\OrderCrudController;
use App\Controller\Admin\DetailCommandeCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(RestaurantCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(ReservationCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(ReservationCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(MenuCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(MenuItemCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(CategorieCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(OrderCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(DetailCommandeCrudController::class)->generateUrl());
        
        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Restaurant', 'fas fa-list', Restaurant::class);
        yield MenuItem::linkToCrud('Table', 'fas fa-list', Table::class);
        yield MenuItem::linkToCrud('Reservation', 'fas fa-list', Reservation::class);
        yield MenuItem::linkToCrud('Menu', 'fas fa-list', Menu::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Categorie::class);
        yield MenuItem::linkToCrud('Foods', 'fas fa-list', Food::class);
        yield MenuItem::linkToCrud('Order', 'fas fa-list', Order::class);
        yield MenuItem::linkToCrud('Detail order', 'fas fa-list', DetailCommande::class);

    }
}
