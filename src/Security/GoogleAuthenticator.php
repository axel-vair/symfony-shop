<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\OAuthRegistrationService;
use http\Exception\RuntimeException;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Classe GoogleAuthenticator
 *
 * Cette classe étend AbstractOAuthAuthenticator et gère spécifiquement l'authentification via Google.
 */
class GoogleAuthenticator extends AbstractOAuthAuthenticator
{
    /**
     * Nom du service OAuth utilisé.
     * Défini sur 'google' pour l'authentification Google.
     */
    protected string $serviceName = 'google';

    /**
     * Constructeur de la classe GoogleAuthenticator.
     *
     * Initialise l'authentificateur avec les dépendances nécessaires.
     * Appelle le constructeur parent avec les mêmes paramètres.
     *
     * @param ClientRegistry $clientRegistry Registre des clients OAuth
     * @param RouterInterface $router Interface de routage Symfony
     * @param UserRepository $userRepository Repository pour les opérations liées aux utilisateurs
     * @param OAuthRegistrationService $registrationService Service pour l'enregistrement des utilisateurs OAuth
     */
    public function __construct(
        ClientRegistry           $clientRegistry,
        RouterInterface          $router,
        UserRepository           $userRepository,
        OAuthRegistrationService $registrationService,
    )
    {
        parent::__construct($clientRegistry, $router, $userRepository, $registrationService);
    }

    /**
     * Récupère l'utilisateur à partir des informations fournies par Google.
     *
     * Cette méthode implémente la méthode abstraite de la classe parente.
     * Elle vérifie que les informations proviennent bien de Google et que l'email est vérifié,
     * puis tente de trouver un utilisateur correspondant dans la base de données.
     *
     * @param ResourceOwnerInterface $resourceOwner Les informations de l'utilisateur fournies par Google
     * @param UserRepository $userRepository Le repository des utilisateurs
     * @return User|null L'utilisateur trouvé ou null si aucun utilisateur ne correspond
     * @throws RuntimeException Si les informations ne proviennent pas de Google ou si l'email n'est pas vérifié
     */
    protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $userRepository): ?User
    {
        // Vérifie que $resourceOwner est bien une instance de GoogleUser
        if (!($resourceOwner instanceof GoogleUser)) {
            throw new RuntimeException("expected instance of GoogleUser");
        }

        // Vérifie que l'email de l'utilisateur est vérifié
        if (true !== ($resourceOwner->toArray()['email_verified'] ?? null)) {
            throw new RuntimeException("email not verified");
        }

        // Recherche un utilisateur dans la base de données correspondant à l'ID Google et à l'email
        return $userRepository->findOneBy([
            'google_id' => $resourceOwner->getId(),
            'email' => $resourceOwner->getEmail()
        ]);
    }
}
