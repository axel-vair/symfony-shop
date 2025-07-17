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
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        // Crée et gère le formulaire
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            // Vérifie si le mot de passe est une chaîne valide
            if (is_string($plainPassword) && !empty($plainPassword)) {
                // Hash le mot de passe
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $plainPassword
                    )
                );

                // Persiste l'utilisateur dans la base de données
                $entityManager->persist($user);
                $entityManager->flush();

                // Message flash de succès
                $this->addFlash('success', 'Votre inscription est un succès ! Vous pouvez maintenant vous connecter.');

                // Redirection vers la page de login
                return $this->redirectToRoute('app_login');
            } else {
                // Gestion de l'erreur si le mot de passe est invalide
                $this->addFlash('error', 'Le mot de passe doit être une chaîne non vide.');
            }
        }

        // Rendu de la vue avec le formulaire d'inscription
        return $this->render('pages/registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
