<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ObjectManager $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $container = $this->client->getContainer();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $container->get('doctrine');
        $this->entityManager = $doctrine->getManager();
    }

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

    private function createProduct(string $name = 'Test Product', float $price = 10.0): Product
    {
        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function testCartPageRequiresLogin(): void
    {
        $this->client->request('GET', '/panier');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect());

        $location = $response->headers->get('Location');
        $this->assertNotNull($location);
        $this->assertStringContainsString('/login', $location);
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

        $location = $response->headers->get('Location');
        $this->assertNotNull($location);
        $this->assertStringContainsString('/login', $location);
    }

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

    public function testDeleteCartRoute(): void
    {
        $user = $this->createUser('user_delete@example.com');
        $this->client->loginUser($user);

        $this->client->request('GET', '/panier/vider');

        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect('/panier'));
    }
}
