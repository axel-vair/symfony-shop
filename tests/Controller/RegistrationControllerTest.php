<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
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
        $form['registration_form[plainPassword]'] = 'password123';

        $client->submit($form);

        // On attend une redirection vers login
        $this->assertResponseRedirects('/login');

        $crawler = $client->followRedirect();
    }


}
