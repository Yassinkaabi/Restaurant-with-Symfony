<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\DetailCommande;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/commandes', name: 'app_orders_')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(OrderRepository $orderRepository): Response
    {
        
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findByUser($this->getUser())
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session, FoodRepository $foodRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('app_home');
        }

        //Le panier n'est pas vide, on crée la commande
        $order = new Order();

        // On remplit la commande
        $order->setUser($this->getUser());

        // On parcourt le panier pour créer les détails de commande
        foreach($panier as $item => $quantity){
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

    #[Route('/show/{id}', name: 'show')]
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
}
