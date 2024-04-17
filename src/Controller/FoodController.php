<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Food;
use App\Form\FoodType;
use App\Repository\CategorieRepository;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FoodController extends AbstractController
{
    #[Route('/admin/food', name: 'app_food_index', methods: ['GET'])]
    public function index(FoodRepository $foodRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied. You need to have the ROLE_ADMIN role.');
        }

        $query = $foodRepository->createQueryBuilder('f')->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15 // Items per page
        );

        // Calculate pagination variables
        // $currentPageItems = count($pagination->getItems());
        // $hasNext = $currentPageItems > 15; // 15 is the number of items per page
        // $currentPage = $pagination->getCurrentPageNumber();
        // $previous = $currentPage > 1 ? $currentPage - 1 : null;
        // $next = $hasNext ? $currentPage + 1 : null;
        // $query = array(); // Add any additional query parameters if needed
        // $pageParameterName = 'page'; // The name of the page parameter in the URL

        // // Render the template with the paginated results and pagination variables
        return $this->render('food/index.html.twig', [
            'food' => $pagination,
            // 'previous' => $previous,
            // 'next' => $next,
            // 'query' => $query,
            // 'pageParameterName' => $pageParameterName,
        ]);
    }

    #[Route('/admin/food/new', name: 'app_food_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied. You need to have the ROLE_ADMIN role.');
        }

        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($food);
            $entityManager->flush();

            return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('food/new.html.twig', [
            'food' => $food,
            'form' => $form,
        ]);
    }

    #[Route('/food/{id}', name: 'app_food_show', methods: ['GET'])]
    public function show(Food $food, CategorieRepository $categorieRepository): Response
    {

        return $this->render('food/show.html.twig', [
            'food' => $food,
            'categorie' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/admin/food/{id}', name: 'app_food_show_admin', methods: ['GET'])]
    public function showFoodAdmin(Food $food, CategorieRepository $categorieRepository): Response
    {

        return $this->render('food/showAdminFood.html.twig', [
            'food' => $food,
            'categorie' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/admin/food/edit/{id}', name: 'app_food_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Food $food, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied. You need to have the ROLE_ADMIN role.');
        }

        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('food/edit.html.twig', [
            'food' => $food,
            'form' => $form,
        ]);
    }

    #[Route('/admin/food/{id}', name: 'app_food_delete', methods: ['POST'])]
    public function delete(Request $request, Food $food, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied. You need to have the ROLE_ADMIN role.');
        }

        if ($this->isCsrfTokenValid('delete' . $food->getId(), $request->request->get('_token'))) {
            $entityManager->remove($food);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_food_index', [], Response::HTTP_SEE_OTHER);
    }
}
