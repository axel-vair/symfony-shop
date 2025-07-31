<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        // Création et stockage d’un client unique réutilisé dans tous les tests
        $this->client = static::createClient();

        // Récupération de l’entity manager depuis le container via ce client
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();

    }

    /**
     * Crée et persiste un utilisateur avec ROLE_USER
     */
    private function createUser(string $email = 'user@example.com'): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('dummy_password');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Crée et persiste un produit simple
     */
    private function createProduct(): Product
    {
        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice(10.0);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function testCartPageRequiresLogin(): void
    {
        $this->client->request('GET', '/panier');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString('/login', $response->headers->get('Location'));
    }

    public function testCartPageLoadsForUser(): void
    {
        $user = $this->createUser('user_cart@example.com');
        $this->client->loginUser($user);

        $this->client->request('GET', '/panier');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testAddToCartRedirectsForUser(): void
    {
        $user = $this->createUser('user_add@example.com');
        $product = $this->createProduct();

        $this->client->loginUser($user);

        $this->client->request('GET', '/panier/add/' . $product->getId());
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/panier'));
    }

    public function testAddToCartForbiddenWhenNotLoggedIn(): void
    {
        $this->client->request('GET', '/panier/add/1');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString('/login', $response->headers->get('Location'));
    }


    /**
     * Helper privé pour tester les routes qui prennent un produit en paramètre ID
     */
    private function doTestProductRoute(string $urlTemplate): void
    {
        $user = $this->createUser('user_product@example.com');
        $product = $this->createProduct();

        $this->client->loginUser($user);

        $url = str_replace('{id}', (string) $product->getId(), $urlTemplate);

        $this->client->request('GET', $url);
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/panier'));
    }

    public function testIncreaseQuantityRoute(): void
    {
        $this->doTestProductRoute('/panier/quantity/increase/{id}');
    }

    public function testDecreaseQuantityRoute(): void
    {
        $this->doTestProductRoute('/panier/quantity/decrease/{id}');
    }

    public function testDeleteOneProductFromCartRoute(): void
    {
        $this->doTestProductRoute('/panier/delete/product/{id}');
    }

    public function testValidateCartRedirectsIfNotAuthenticated(): void
    {
        $this->client->request('GET', '/panier/valider');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString('/login', $response->headers->get('Location'));
    }

    public function testValidateCartBehavior(): void
    {
        $user = $this->createUser('user_validate@example.com');
        $this->client->loginUser($user);

        $this->client->request('GET', '/panier/valider');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect());

        $location = $response->headers->get('Location');
        $this->assertTrue(
            $location === '/mon-compte/commandes' || $location === '/panier',
            sprintf('La redirection doit être vers la page commandes ou le panier, ici : %s', $location)
        );
    }
}
