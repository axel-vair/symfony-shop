<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public const SCOPE = [
        'google' => []
    ];
    /**
     * Gère le processus de connexion des utilisateurs.
     *
     * Cette méthode affiche le formulaire de connexion et gère les erreurs de connexion.
     * Elle récupère la dernière erreur d'authentification (s'il y en a une) et le dernier
     * nom d'utilisateur entré, puis les passe à la vue pour affichage.
     *
     * @param AuthenticationUtils $authenticationUtils Utilitaire pour obtenir des informations sur l'authentification
     * @return Response La réponse HTTP contenant la vue du formulaire de connexion
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier nom d'utilisateur entré
        $lastUsername = $authenticationUtils->getLastUsername();

        // Ajoute un message flash pour informer l'utilisateur qu'il est connecté
        // Note : Ce message flash sera affiché après une connexion réussie
        $this->addFlash('success', 'Vous êtes connecté.e !');

        // Rend la vue du formulaire de connexion en passant les informations nécessaires
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/oauth/connect/{service}', name: 'auth_oauth_connect', methods: ['GET'])]
    public function connect(string $service, ClientRegistry $clientRegistry): RedirectResponse
    {
        if(! in_array($service, array_keys(self::SCOPE), true)) {
            throw $this->createAccessDeniedException();
        }
        return $clientRegistry
            ->getClient($service)
            ->redirect(self::SCOPE[$service], []);
    }


    #[Route(path: '/oauth/check/{service}', name: 'auth_oauth_check', methods: ['GET', 'POST'])]
    public function check(): Response
    {
        return new Response(status:200);
    }
    /**
     * Gère le processus de déconnexion des utilisateurs.
     *
     * Cette méthode est en réalité un placeholder. La déconnexion réelle est gérée
     * par le firewall de Symfony. Cette méthode ne sera jamais exécutée, mais doit
     * exister pour que Symfony puisse générer la route de déconnexion.
     *
     * @throws \LogicException Cette exception ne sera jamais lancée en pratique
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Ajoute un message flash pour informer l'utilisateur qu'il a été déconnecté
        // Note : Ce message ne sera pas affiché car cette méthode n'est jamais exécutée
        $this->addFlash('success', 'Vous avez été déconnecté avec succès.');

        // Cette exception ne sera jamais lancée, car cette méthode est interceptée par le firewall
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
