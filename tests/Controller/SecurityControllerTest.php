<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

    public function testLoginWithInvalidCredentialsShowsError(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'nonexistent@example.com',
            '_password' => 'wrongpassword',
        ]);

        $client->submit($form);

        $crawler = $client->followRedirect();

        // Assert presence of error div (classe bootstrap alert-danger)
        $this->assertSelectorExists('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials');
    }

    // Note : ce test nécessite une base de données avec un utilisateur existant et mot de passe connu
    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Remplace par un utilisateur valide déjà présent en base et son mot de passe en clair (test uniquement)
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'validuser@example.com',
            '_password' => 'validpassword',
            '_csrf_token' => $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate'),
        ]);

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('body', 'Vous êtes connecté');
    }
}
