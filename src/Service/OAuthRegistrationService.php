<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * Classe OAuthRegistrationService
 *
 * Cette classe gère l'enregistrement des utilisateurs authentifiés via OAuth.
 */
class OAuthRegistrationService
{
    /**
     * Constante représentant le mot de passe par défaut pour les utilisateurs OAuth.
     * Ce mot de passe n'est pas destiné à être utilisé pour une authentification réelle.
     */
    private const OAUTH_PASSWORD = 'OAUTH_USER';

    /**
     * Constructeur de la classe OAuthRegistrationService.
     *
     * @param UserRepository $userRepository Le repository pour les opérations liées aux utilisateurs
     */
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Persiste un nouvel utilisateur dans la base de données à partir des informations OAuth.
     *
     * Cette méthode crée un nouvel utilisateur avec les informations fournies par le provider OAuth,
     * définit la méthode d'authentification sur 'google', et utilise un mot de passe par défaut.
     * L'utilisateur est ensuite ajouté à la base de données.
     *
     * @param ResourceOwnerInterface $resourceOwner Les informations de l'utilisateur fournies par le provider OAuth
     * @return User L'utilisateur nouvellement créé et persisté
     */
    public function persist(ResourceOwnerInterface $resourceOwner): User
    {
        $data = $resourceOwner->toArray();
        $email = $data['email'] ?? null;

        if (!$email) {
            throw new \RuntimeException("L'email OAuth est absent.");
        }

        $user = (new User())
            ->setEmail($email)
            ->setGoogleId($resourceOwner->getId());

        $user->setAuthMethod('google');
        $user->setPassword(self::OAUTH_PASSWORD);

        $this->userRepository->add($user, true);

        return $user;
    }

}
