<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FoodRepository;
use App\Repository\CommentRepository;
use App\Repository\CategorieRepository;
use App\Repository\MenuRepository;
use App\Entity\Comment;
use App\Entity\Menu;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request,FoodRepository $foodRepository,MenuRepository $menuRepository,CategorieRepository $categorieRepository, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();

        $user = $this->getUser();

        $comment->setUser($user);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash(
               'success',
               'Votre commentaire a été envoyé'
            );
            return $this->redirectToRoute('app_home');
        }

        // $menus = $this->getDoctrine()->getRepository(Menu::class)->findBy(['menu' => ['PETIT-DÉJEUNER','DÉJEUNER', 'DÎNER']], null, 3);
        $menus = $menuRepository->findSpecialMenus();
        return $this->render('home/index.html.twig', [
            // 'comment' => $comment,
            'form' => $form->createView(),
            'comments' => $commentRepository->findAll(),
            'menus' => $menus,
            'food' => $foodRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            

        ]);
    }

}
