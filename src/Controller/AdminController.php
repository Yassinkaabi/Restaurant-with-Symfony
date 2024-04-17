<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Food;
use App\Entity\Order;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        // Query to get the number of users
        $userRepository = $entityManager->getRepository(User::class);
        $userCount = $userRepository->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Query to get the number of foods
        $foodRepository = $entityManager->getRepository(Food::class);
        $foodCount = $foodRepository->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Query to get the number of categories
        $categoryRepository = $entityManager->getRepository(Categorie::class);
        $categoryCount = $categoryRepository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Query to get the number of reservation
        $ReservationRepository = $entityManager->getRepository(Reservation::class);
        $reservationCount = $ReservationRepository->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Query to get the number of reservation
        $orderRepository = $entityManager->getRepository(Order::class);
        $orderCount = $orderRepository->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Render the Twig template with the data
        return $this->render('admin/index.html.twig', [
            'userCount' => $userCount,
            'foodCount' => $foodCount,
            'categoryCount' => $categoryCount,
            'reservationCount' => $reservationCount,
            'orderCount' => $orderCount,
        ]);
    }
}
