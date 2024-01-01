<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentRepository;
use App\Form\CommentType;
use App\Entity\Comment;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(Request $request, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
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
        return $this->render('about/index.html.twig', [
            'form' => $form->createView(),
            'comments' => $commentRepository->findAll(),
        ]);
    }
}
