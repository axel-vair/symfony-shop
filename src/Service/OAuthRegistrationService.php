<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class OAuthRegistrationService
{
    private const OAUTH_PASSWORD = 'OAUTH_USER';
    public function __construct(private UserRepository $userRepository)
    {}

    public function persist(ResourceOwnerInterface $resourceOwner): User
    {
        $user = (new User())
            ->setEmail($resourceOwner->getEmail())
            ->setGoogleId($resourceOwner->getId());
            $user->setAuthMethod('google');
            $user->setPassword(self::OAUTH_PASSWORD);

        $this->userRepository->add($user, true);
        return $user;
    }
}