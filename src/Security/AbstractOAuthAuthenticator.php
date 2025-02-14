<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Service\OAuthRegistrationService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

abstract class AbstractOAuthAuthenticator extends OAuth2Authenticator
{
    use TargetPathTrait;

    protected OAuthRegistrationService $registrationService;

    protected string $serviceName = '';

    /**
     * Constructeur de la classe.
     * Initialise les dépendances nécessaires pour l'authentification OAuth.
     *
     * @param ClientRegistry $clientRegistry Registre des clients OAuth
     * @param RouterInterface $router Interface de routage Symfony
     * @param UserRepository $userRepository Repository pour les opérations liées aux utilisateurs
     * @param OAuthRegistrationService $registrationService Service pour l'enregistrement des utilisateurs OAuth
     */

    public function __construct(
        protected ClientRegistry  $clientRegistry,
        protected RouterInterface $router,
        protected UserRepository  $userRepository,
        OAuthRegistrationService  $registrationService

    )
    {
        $this->registrationService = $registrationService;

    }

    /**
     * Récupère l'identifiant unique de l'utilisateur à partir du ResourceOwner.
     * Dans ce cas, l'email est utilisé comme identifiant.
     *
     * @param ResourceOwnerInterface $resourceOwner Objet contenant les informations de l'utilisateur OAuth
     * @return string L'email de l'utilisateur
     */
    protected function getUserIdentifier(ResourceOwnerInterface $resourceOwner): string
    {
        return $resourceOwner->getEmail();
    }

    /**
     * Vérifie si l'authentificateur supporte la requête actuelle.
     * Retourne true si la route est 'auth_oauth_check' et le service correspond à $this->serviceName.
     *
     * @param Request $request La requête HTTP actuelle
     * @return bool|null True si l'authentificateur supporte la requête, null sinon
     */
    public function supports(Request $request): ?bool
    {
        return 'auth_oauth_check' === $request->attributes->get('_route') &&
            $request->get('service') === $this->serviceName;
    }

    /**
     * Gère le succès de l'authentification.
     * Redirige vers la page cible si elle existe, sinon vers la page d'accueil.
     *
     * @param Request $request La requête HTTP
     * @param TokenInterface $token Le token d'authentification
     * @param string $firewallName Le nom du firewall utilisé
     * @return Response|null La réponse de redirection
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->router->generate('app_default'));
    }

    /**
     * Gère l'échec de l'authentification.
     * Enregistre l'erreur dans la session et redirige vers la page de connexion OAuth.
     *
     * @param Request $request La requête HTTP
     * @param AuthenticationException $exception L'exception d'authentification
     * @return Response|null La réponse de redirection
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }
        $service = $request->get('service', 'google');

        return new RedirectResponse($this->router->generate('auth_oauth_connect', ['service' => $service]));
    }

    /**
     * Authentifie l'utilisateur en utilisant les informations OAuth.
     * Crée un nouvel utilisateur si nécessaire.
     *
     * @param Request $request La requête HTTP
     * @return SelfValidatingPassport Le passeport d'authentification
     */
    public function authenticate(Request $request): SelfValidatingPassport
    {
        $credentials = $this->fetchAccessToken($this->getClient());
        $resourceOwner = $this->getRessourceOwnerFromCredentials($credentials);
        $user = $this->getUserFromResourceOwner($resourceOwner, $this->userRepository);

        if (null == $user) {
            $user = $this->registrationService->persist($resourceOwner);
        }
        return new SelfValidatingPassport(
            userBadge: new UserBadge($this->getUserIdentifier($resourceOwner), fn() => $user),
            badges: [
                new RememberMeBadge()
            ]
        );
    }

    /**
     * Récupère le client OAuth approprié.
     *
     * @return OAuth2ClientInterface Le client OAuth
     */
    private function getClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient($this->serviceName);
    }

    /**
     * Récupère les informations de l'utilisateur à partir des credentials OAuth.
     *
     * @param AccessToken $credentials Le token d'accès OAuth
     * @return ResourceOwnerInterface Les informations de l'utilisateur
     */
    protected function getRessourceOwnerFromCredentials(AccessToken $credentials): ResourceOwnerInterface
    {
        return $this->getClient()->fetchUserFromToken($credentials);
    }

    /**
     * Méthode abstraite à implémenter dans les classes enfants.
     * Doit récupérer ou créer un utilisateur à partir des informations OAuth.
     *
     * @param ResourceOwnerInterface $resourceOwner Les informations de l'utilisateur OAuth
     * @param UserRepository $userRepository Le repository des utilisateurs
     * @return mixed L'utilisateur correspondant
     */
    abstract protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $userRepository);
}
