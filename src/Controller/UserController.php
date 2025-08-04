<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    /**
     * Gère l'édition du profil utilisateur.
     *
     * Cette méthode permet à un utilisateur authentifié de modifier son profil.
     * Elle vérifie l'authentification, l'autorisation, et la validité du mot de passe actuel avant d'effectuer les modifications.
     *
     * @param User $user L'utilisateur à éditer
     * @param Request $request La requête HTTP
     * @param EntityManagerInterface $manager Le gestionnaire d'entités
     * @param UserPasswordHasherInterface $hasher Le service de hachage de mot de passe
     * @return Response La réponse HTTP (redirection ou formulaire d'édition)
     */
    #[Route('/utilisateur/edition/{id}', name: 'app_profil', methods: ['GET', 'POST'])]
    public function edit(
        User $user,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        // Vérifie que l'utilisateur est complètement authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUser = $this->getUser();

        // Vérifie que l'utilisateur actuel est bien authentifié et que l'utilisateur modifie son propre profil
        if (!$currentUser instanceof User || $currentUser->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce profil.');
        }
       /* if ($currentUser === null || $currentUser->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce profil.');
        }*/

        // Crée et traite le formulaire d'édition
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();

            // Vérifie que le mot de passe actuel est une chaîne de caractères et non vide
            if (!is_string($plainPassword) || empty($plainPassword)) {
                $this->addFlash('error', 'Mot de passe incorrect. Votre profil n\'a pas été modifié.');
                return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
            }

            // Vérifie que le mot de passe actuel est correct
            if (!$hasher->isPasswordValid($user, $plainPassword)) {
                $this->addFlash('error', 'Mot de passe incorrect. Votre profil n\'a pas été modifié.');
                return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
            }

            // Enregistre les modifications
            $manager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié.');
            return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
        }

        // Affiche le formulaire d'édition
        return $this->render('pages/user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
