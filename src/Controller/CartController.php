<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, FoodRepository $foodRepository): Response
    {
        $panier = $session->get('panier', []);
        // dd($panier);

        $data = [];
        $total = 0;

        foreach($panier as $id => $quantity) {
            $food = $foodRepository->find($id);
            // Check if $food is not null before accessing its methods
            if ($food !== null) {
                $data[] = [
                    'food' => $food,
                    'quantity' => $quantity
                ];
                $total += $food->getPrice() * $quantity;
            } else {
                // Handle the case where $food is null (e.g., log an error, skip, etc.)

            }
        }
        return $this->render('cart/index.html.twig', compact('data' , 'total'));
    }


    #[Route('/add/{id}', name: 'add')]
    public function add(Food $food , SessionInterface $session): Response
    {
        $id = $food->getId();

        $panier = $session->get('panier', []);

        if(empty($panier[$id])){
            $panier[$id]= 1;
        }else {
            $panier[$id]++;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/decrement/{id}', name: 'decrement')]
    public function decrement(Food $food , SessionInterface $session): Response
    {
        $id = $food->getId();

        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            if ($panier[$id] > 1) {
                $panier[$id]--;
            }else {
                unset($panier[$id]);
        }
    }

        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Food $food, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $food->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
        
        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove', name: 'remove')]
    public function remove(SessionInterface $session): Response
    {    
        $session->remove( 'panier');
        return $this->redirectToRoute('cart_index');
    }

}
