<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserForm;
use App\Repository\UserRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Command\UserPasswordHashCommand;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/users', name: 'users')]
    public function users(Request $request, UserRepository $userRepository): Response
    {
        return $this->render(
            'registration/users.html.twig',
            [
                'users' => $userRepository->findAll()
            ]
        );
    }

    #[Route('/admin/users/create', name: 'create_user')]
    public function create(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password if it's set
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $encodedPassword = $passwordEncoder->hashPassword($user, $plainPassword);
                $user->setPassword($encodedPassword);
            }
            $entityManager->persist($user); // Persist the new user entity
            $entityManager->flush();

            // Redirect to some page after editing
            return $this->redirectToRoute('users');
        }
        return $this->render(
            'user/create.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }

    #[Route('/admin/users/{username}', name: 'edit_user')]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            // Redirect to some page after editing
            return $this->redirectToRoute('users');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }
}
