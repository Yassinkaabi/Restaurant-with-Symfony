<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\DetailCommandeRepository;
use App\Repository\OrderRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted as AttributeIsGranted;

class ReservationController extends AbstractController
{
    // #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/reservation', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findByUser($this->getUser()),
        ]);
    }

    #[AttributeIsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/admin/reservation', name: 'app_reservation_admin', methods: ['GET'])]
    public function fetchAllReservartion(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/fetchReservation.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/reservation/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();

        // Get the currently authenticated user
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        // Set the user for the reservation
        $reservation->setUser($user);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/reservation/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation, Order $order, OrderRepository $orderRepository, DetailCommandeRepository $detailCommandeRepository): Response
    {
        $user = $this->getUser();
        $userOrders = $orderRepository->findBy(['user' => $user]);

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
            'userOrders' => $userOrders,
        ]);
    }

    #[Route('/admin/reservation/{id}', name: 'app_reservation_show_admin', methods: ['GET'])]
    public function showAdmin(Reservation $reservation, ReservationRepository $reservationRepository, OrderRepository $orderRepository): Response
    {
        $user = $reservation->getUser();
        $userOrders = $user->getOrderId();

        return $this->render('reservation/showAdmin.html.twig', [
            'reservation' => $reservation,
            'userOrders' => $userOrders,
        ]);
    }

    #[Route('/admin/reservation/edit/{id}', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/admin/reservation/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
