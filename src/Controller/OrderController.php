<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\DetailCommande;
use App\Entity\Food;
use App\Form\OrderType;
use App\Repository\DetailCommandeRepository;
use App\Repository\OrderRepository;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_orders_index')]
    public function index(OrderRepository $orderRepository): Response
    {

        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findByUser($this->getUser())
        ]);
    }

    #[Route('/admin/orders', name: 'app_orders_admin')]
    public function fetchAllOrders(OrderRepository $orderRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied. You need to have the ROLE_ADMIN role.');
        }

        return $this->render('order/fetchAllOrders.html.twig', [
            'orders' => $orderRepository->findAll()
        ]);
    }

    #[Route('/ajout', name: 'app_orders_add')]
    public function add(SessionInterface $session, FoodRepository $foodRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if ($panier === []) {
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('cart_index');
        }

        //Le panier n'est pas vide, on crée la commande
        $order = new Order();

        // On remplit la commande
        $order->setUser($this->getUser());

        // On parcourt le panier pour créer les détails de commande
        foreach ($panier as $item => $quantity) {
            $orderDetails = new DetailCommande();

            // On va chercher le produit
            $food = $foodRepository->find($item);

            $price = $food->getPrice() * $quantity;

            // On crée le détail de commande
            $orderDetails->setFood($food);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addDetailsCmd($orderDetails);
        }

        // On persiste et on flush
        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message', 'Commande créée avec succès');
        return $this->redirectToRoute('app_home');
    }

    #[Route('/show/{id}', name: 'app_orders_show')]
    public function show(Order $order)
    {
        // Check if the user has access to this order
        $user = $this->getUser();

        if (!$user || $order->getUser() !== $user) {
            throw new AccessDeniedException("Access denied");
        }

        // Render the order details
        return $this->render('order/show.html.twig', ['order' => $order]);
    }

    #[Route('/order/{id}', name: 'app_orders_show_admin')]
    public function showOrderAllUser(Order $order, Food $food, DetailCommandeRepository $detailCommandeRepository)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied. You need to have the ROLE_ADMIN role.');
        }
        $orderDetails = $detailCommandeRepository->findDetailsOrder($order->getId());
        $foodDetails = $detailCommandeRepository->findFoodDetailsForOrder($food->getId());
        // dd($foodDetails);    
        // Render the order details
        return $this->render('order/showOrderAllUser.html.twig', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'foodDetails' => $foodDetails

        ]);
    }


    #[Route('/{id}/edit', name: 'app_orders_edit', methods: ['GET', 'POST'])]
    public function edit(Order $order, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Check if the user has the ROLE_ADMIN role
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied');
        }

        // Edit the attributes of the order directly
        if ($order->getStatus() === 'success') {
            $order->setStatus('pending');
        } elseif ($order->getStatus() === 'pending') {
            $order->setStatus('success');
        }

        // Persist the changes to the order entity
        $entityManager->flush();

        // Redirect to the admin orders page after editing
        return $this->redirectToRoute('app_orders_admin');
    }

    #[Route('/order/{orderId}/food-details', name: 'app_orders_order_food_details')]
    public function getOrderFoodDetails(int $orderId, DetailCommandeRepository $detailCommandeRepository): Response
    {
        // Retrieve food details for the given order ID
        $foodDetails = $detailCommandeRepository->findFoodDetailsForOrder($orderId);

        // Render the template with the retrieved food details
        return $this->render('order/show.html.twig', [
            'foodDetails' => $foodDetails,
        ]);
    }
}
