<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContactPageRendersCorrectly(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        // Vérifier que la page se charge correctement
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Laissez-nous vos coordonnées');
        $this->assertSelectorExists('form');
    }

    public function testSubmitFormSuccessfully(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        // Soumettre un formulaire valide
        $form = $crawler->selectButton('Envoyer le message')->form();
        $form['contact[email]'] = 'test@example.com';
        $form['contact[name]'] = 'John Doe';
        $form['contact[message]'] = 'Test message';

        $client->submit($form);

        // Vérifier si on est redirigé après le succès
        $this->assertResponseRedirects('/contact');
        $client->followRedirect();

        // Vérifier qu'un message flash de succès est affiché
        $this->assertSelectorTextContains('.alert-success', 'Votre message a été envoyé !');
    }

    public function testSubmitFormWithInvalidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        // Soumettre un formulaire invalide
        $form = $crawler->selectButton('Envoyer le message')->form();
        $form['contact[email]'] = '';
        $form['contact[name]'] = '';
        $form['contact[message]'] = '';

        $client->submit($form);

        // Vérifier si une erreur est affichée
        $this->assertSelectorTextContains('.alert-danger', 'Une erreur est survenue !');
    }
}
