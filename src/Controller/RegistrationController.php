<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    /**
     * Gère le processus d'inscription d'un nouvel utilisateur.
     *
     * Cette méthode crée et traite un formulaire d'inscription. Si le formulaire est soumis et valide,
     * elle crée un nouvel utilisateur, hache son mot de passe, l'enregistre dans la base de données,
     * affiche un message de confirmation, et redirige vers la page de connexion.
     *
     * @param Request $request L'objet Request contenant les données de la requête HTTP
     * @param UserPasswordHasherInterface $userPasswordHasher Le service pour hacher les mots de passe
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités pour interagir avec la base de données
     * @return Response La réponse HTTP (soit un rendu du formulaire d'inscription, soit une redirection)
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance de l'entité User
        $user = new User();

        // Crée le formulaire d'inscription
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Traite la requête et remplit l'objet $user avec les données soumises
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Hache le mot de passe en clair
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Persiste le nouvel utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajoute un message flash pour confirmer l'inscription
            $this->addFlash('success', 'Votre inscription est un succès ! Vous pouvez maintenant vous connecter.');

            // Redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire n'a pas été soumis ou n'est pas valide, affiche le formulaire d'inscription
        return $this->render('pages/registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}