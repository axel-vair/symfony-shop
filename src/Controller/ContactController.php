<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
       $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password


            $entityManager->persist($contact);
            $entityManager->flush();

            // do anything else you need here, like send an email
             $this->addFlash('notice', "Votre message a été envoyé !");
            return $this->redirectToRoute('app_contact');

        }



        return $this->render('pages/contact/contact.html.twig', [
           'contactForm' => $form,
        ]);
    }
}