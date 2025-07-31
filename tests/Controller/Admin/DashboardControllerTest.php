<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    public function testAccessDeniedWithoutAdminRole()
    {
        $client = static::createClient();

        // Accès à /admin sans authentification
        $client->request('GET', '/admin');

        $response = $client->getResponse();

        $this->assertTrue(
            $response->isRedirect() || $response->getStatusCode() === 403,
            'Les utilisateurs non authentifiés ou sans ROLE_ADMIN ne doivent pas accéder à /admin'
        );
    }

    public function testAdminCanAccessAndRedirectsToProductCrud()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $em = $container->get('doctrine')->getManager();

        // Création et persistance d'un utilisateur avec le rôle ROLE_ADMIN
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('dummy');

        $em->persist($user);
        $em->flush();

        // Connexion de cet utilisateur
        $client->loginUser($user);

        // Requête GET sur /admin (pour tester la redirection vers CRUD produit)
        $client->request('GET', '/admin');

        $response = $client->getResponse();

        // Assert que la réponse est une redirection
        $this->assertTrue($response->isRedirect(), 'La réponse doit être une redirection.');

        // Récupération de l’en-tête Location
        $location = $response->headers->get('Location');

        // Assert que Location est bien une chaîne (non null)
        $this->assertIsString($location, 'L’en-tête Location doit être une chaîne.');

        // Assert que l'URL contient bien ProductCrudController
        $this->assertStringContainsString('ProductCrudController', $location, 'La redirection doit pointer vers ProductCrudController.');

        // Suivre la redirection vers la page des produits
        $crawler = $client->followRedirect();

        // Assert que la page contient un h1 avec "Product"
        $this->assertSelectorTextContains('h1', 'Product');
    }
}
