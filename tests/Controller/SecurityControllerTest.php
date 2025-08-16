<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageLoads(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');
        $this->assertSelectorExists('form', 'Formulaire de connexion absent');
        $this->assertSelectorExists('input[name="_username"]');
        $this->assertSelectorExists('input[name="_password"]');
        $this->assertSelectorExists('input[name="_csrf_token"]');
    }

    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();

        $container = $client->getContainer();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $user = new User();
        $user->setEmail('validuser@example.com');

        $hashedPassword = $passwordHasher->hashPassword($user, 'validpassword');
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'validuser@example.com',
            '_password' => 'validpassword',
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('body', 'Vous êtes bien connecté.e');
    }
}
