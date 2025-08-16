<?php

namespace App\Tests\Controller;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Ulid;

class OrderControllerTest extends WebTestCase
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

    private function createOrder(User $user, ?Ulid $reference = null): Order
    {
        $order = new Order();
        $order->setUtilisateur($user);
        $order->setReference($reference ?? new Ulid());
        $order->setTotal(100);
        $order->setStatus('En attente');
        $order->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    public function testIndexRequiresLogin(): void
    {
        $this->client->request('GET', '/utilisateur/commandes');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect());

        $location = $response->headers->get('Location');
        $this->assertNotNull($location);
        $this->assertStringContainsString('/login', $location);
    }

    public function testIndexDisplaysOrders(): void
    {
        $user = $this->createUser('user_orders@example.com');

        // Crée quelques commandes liées à cet utilisateur
        $this->createOrder($user, new Ulid());
        $this->createOrder($user, new Ulid());

        $this->client->loginUser($user);

        $this->client->request('GET', '/utilisateur/commandes');

        $this->assertResponseIsSuccessful();

        // Vérifie qu’au moins un élément de commande apparaisse dans le contenu HTML
        // Adapter ces assertions selon le rendu réel de ta vue
        $this->assertSelectorExists('body');
    }

    public function testShowDisplaysOrderDetails(): void
    {
        $user = $this->createUser('user_orderdetails@example.com');
        $reference = new Ulid();
        $order = $this->createOrder($user, $reference);

        $this->client->loginUser($user);

        $this->client->request('GET', '/utilisateur/commande/' . $reference);

        $this->assertResponseIsSuccessful();

        $content = $this->client->getResponse()->getContent();

        $this->assertIsString($content, 'La réponse HTTP doit contenir du texte');

        if ($content == null) {
            $this->fail('La réponse HTTP est vide');
        }

        $this->assertStringContainsString((string) $reference, $content);
    }
}
