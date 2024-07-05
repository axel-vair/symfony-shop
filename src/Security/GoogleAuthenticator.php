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

class GoogleAuthenticator extends AbstractOAuthAuthenticator
{
    protected string $serviceName = 'google';
    public function __construct(
        ClientRegistry $clientRegistry,
        RouterInterface $router,
        UserRepository $userRepository,
        OAuthRegistrationService $registrationService,
    ) {
        parent::__construct($clientRegistry, $router, $userRepository, $registrationService);
    }
    protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $userRepository): ?User
    {
        if(!($resourceOwner instanceof GoogleUser)){
            throw new RuntimeException("expected instance of GoogleUser");
        }
        if(true !== ($resourceOwner->toArray()['email_verified'] ?? null))
        {
            throw new RuntimeException("email not verified");
        }

        return $userRepository->findOneBy([
            'google_id' => $resourceOwner->getId(),
            'email' => $resourceOwner->getEmail()
        ]);
    }
}
