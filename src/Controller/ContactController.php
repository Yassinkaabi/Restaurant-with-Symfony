<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/admin/contact', name: 'contact_admin')]
    public function index(ContactRepository $contactRepository) {
        
        return $this->render('contact/contact.html.twig',[
            'contact' => $contactRepository->findAll()
        ]);
    }


    #[Route('/contact', name: 'app_contact')]
    public function new(Request $request,EntityManagerInterface $manager, MailerInterface $mailer): Response {
        $contact = new Contact();

        if ($this->getUser()) {
            $contact->setUsername($this->getUser()->getUsername())
                    ->setEmail($this->getUser()->getEmail());
        }
        
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            //Email
            $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('yassinkaabi14@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->html($contact->getSubject())
            ->htmlTemplate('emails/contact.html.twig')
        
            // pass variables (name => value) to the template
            ->context([
                'contact' => $contact
            ]);

        $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre demande a été envoyé avec succès !'
            );

            return $this->redirectToRoute('app_contact');
        } else {
            $this->addFlash(
                'danger',
                $form->getErrors()
            );
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
