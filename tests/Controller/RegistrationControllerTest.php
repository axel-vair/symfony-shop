<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{


    public function testRegisterPageLoadsSuccessfully(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('button', 'S\'inscrire');
    }

    public function testRegisterFormSubmission(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form();
        $form['registration_form[email]'] = 'bestMail@ever.com';
        $form['registration_form[plainPassword]'] = 'password';

        $client->submit($form);

        $this->assertResponseRedirects('/profile');
        $client->followRedirect();


        $this->assertSelectorExists('form');
    }


//    public function testRegistrationFormWithInvalidEmail()
//    {
//        $client = static::createClient();
//        $client->request('GET', '/register');
//
//        // Soumet le formulaire avec un email invalide
//        $client->submitForm('S\'inscrire', [
//            'registration_form[email]' => 'invalid-email',
//            'registration_form[plainPassword]' => 'Password123',
//        ]);
//
//        // Vérifie que le formulaire reste sur la même page (HTTP 200)
//        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//
//        // Vérifie que le message d'erreur pour un email invalide est affiché
//        $this->assertSelectorTextContains('.form-error', 'Veuillez entrer une adresse e-mail valide.');
//    }

//    public function testRegistrationFormWithShortPassword()
//    {
//        $client = static::createClient();
//        $client->request('GET', '/register');
//
//        // Soumet le formulaire avec un mot de passe trop court
//        $client->submitForm('S\'inscrire', [
//            'registration_form[email]' => 'john.doe@example.com',
//            'registration_form[plainPassword]' => '123', // Mot de passe trop court
//        ]);
//
//        // Vérifie que le formulaire reste sur la même page (HTTP 200)
//        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//
//        // Vérifie que le message d'erreur pour le mot de passe court est affiché
//        $this->assertSelectorTextContains('.form-error', 'Votre mot de passe doit contenir au moins 6 caractères');
//    }

//    public function testRegistrationFormWithBlankPassword()
//    {
//        $client = static::createClient();
//        $client->request('GET', '/register');
//
//        // Soumet le formulaire sans mot de passe
//        $client->submitForm('S\'inscrire', [
//            'registration_form[email]' => 'john.doe@example.com',
//            'registration_form[plainPassword]' => '', // Mot de passe vide
//        ]);
//
//        // Vérifie que le formulaire reste sur la même page (HTTP 200)
//        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//
//        // Vérifie que le message d'erreur pour le mot de passe vide est affiché
//        $this->assertSelectorTextContains('.form-error', 'Veuillez entrer un mot de passe');
//    }
    /**
     * Test de soumission avec des données invalides
     */
//    public function testRegistrationFormWithInvalidData()
//    {
//        // Crée un client HTTP pour simuler les requêtes
//        $client = static::createClient();
//
//        // Accède à la page d'inscription
//        $client->request('GET', '/register');
//
//        // Vérifie que la page d'inscription se charge correctement (code 200)
//        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//
//        // Soumet le formulaire avec des données invalides
//        $client->submitForm('S\'inscrire', [
//            'registration_form[firstName]' => '',
//            'registration_form[lastName]' => '',
//            'registration_form[email]' => 'invalid-email',
//            'registration_form[plainPassword]' => '123',
//            'registration_form[plainPasswordRepeat]' => '123',
//        ]);
//
//        // Vérifie que la page reste sur le formulaire d'inscription
//        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//
//        // Vérifie que des erreurs sont présentes pour les champs invalides
//        $this->assertSelectorTextContains('.form-error', 'This value is not valid.');
//        $this->assertSelectorTextContains('.form-error', 'This value should not be blank.');
//        $this->assertSelectorTextContains('.form-error', 'Password cannot have less than 8 characters.');
//    }
}
