<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistrationPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Inscription');
        $this->assertSelectorExists('form');
    }

    public function testRegistrationWithValidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton("S'inscrire")->form();

        $mail = 'regtest'.uniqid().'@exemple.com';
        $form['registration_form[email]'] = $mail;
        $form['registration_form[plainPassword][first]'] = 'password123';
        $form['registration_form[plainPassword][second]'] = 'password123';


        $client->submit($form);

        // On attend une redirection vers login
        $this->assertResponseRedirects('/login');

        $client->followRedirect();
    }

    public function testRegistrationWithInvalidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton("S'inscrire")->form();
        $form['registration_form[plainPassword][first]'] = '';
        $form['registration_form[plainPassword][second]'] = '';
        $client->submit($form);
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Veuillez saisir un email', $client->getResponse()->getContent());
        $this->assertStringContainsString('Veuillez entrer un mot de passe', $client->getResponse()->getContent());
    }

}
