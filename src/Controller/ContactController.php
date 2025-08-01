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
    /**
     * Gère la page de contact et le traitement du formulaire de contact.
     *
     * Cette méthode crée et traite un formulaire de contact. Si le formulaire est soumis et valide,
     * elle enregistre le message de contact dans la base de données, affiche un message de confirmation,
     * et redirige l'utilisateur vers la page de contact. Si le formulaire n'est pas soumis ou n'est pas valide,
     * elle affiche simplement le formulaire.
     *
     * @param Request $request L'objet Request contenant les données de la requête HTTP
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités pour interagir avec la base de données
     * @return Response La réponse HTTP (soit un rendu de la page de contact, soit une redirection)
     */
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance de l'entité Contact
        $contact = new Contact();

        // Crée le formulaire de contact en utilisant ContactType
        $form = $this->createForm(ContactType::class, $contact);

        // Traite la requête et remplit l'objet $contact avec les données soumises
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste l'entité Contact dans la base de données
            $entityManager->persist($contact);
            $entityManager->flush();

            // Ajoute un message flash pour informer l'utilisateur que son message a été envoyé
            $this->addFlash('success', "Votre message a été envoyé !");

            // Redirige l'utilisateur vers la page de contact
            return $this->redirectToRoute('app_contact');
        }elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Une erreur est survenue !");

        }

        // Si le formulaire n'a pas été soumis ou n'est pas valide, affiche la page de contact avec le formulaire
        return $this->render('pages/contact/contact.html.twig', [
            'contactForm' => $form,
        ]);
    }
}
