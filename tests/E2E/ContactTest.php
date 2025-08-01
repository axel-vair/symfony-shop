<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

/**
 * @group panther
 */
class ContactTest extends PantherTestCase
{
    public function testFormDisplay(): void
    {
        $client = self::createPantherClient();
        $crawler = $client->request('GET', '/contact');

        // Vérifie que la page se charge correctement
        $this->assertSelectorTextContains('h2', 'Laissez-nous vos coordonnées');
        $this->assertSelectorExists('form'); // Vérifie la présence du formulaire
    }

    public function testFormSubmissionValid(): void
    {
        $client = self::createPantherClient();
        $crawler = $client->request('GET', '/contact');

        // Remplir et soumettre le formulaire
        $form = $crawler->selectButton('Envoyer le message')->form();
        $form['contact[email]'] = 'john@example.com';
        $form['contact[name]'] = 'John Doe';
        $form['contact[message]'] = 'Ceci est un message de test.';

        // Soumettre le formulaire (Panther suit automatiquement la redirection)
        $client->submit($form);

        // Vérifier que le message de succès est affiché sur la page après redirection
        $crawler = $client->getCrawler();
        $this->assertSelectorTextContains('.alert-success', 'Votre message a été envoyé !');
    }


    public function testFormSubmissionInvalid(): void
    {
        $client = self::createPantherClient();
        $crawler = $client->request('GET', '/contact');

        // Soumettre un formulaire vide
        $form = $crawler->selectButton('Envoyer le message')->form();
        $form['contact[email]'] = '';
        $form['contact[name]'] = '';
        $form['contact[message]'] = '';

        // Soumettre le formulaire (Panther suit automatiquement la redirection)
        $client->submit($form);

        // Vérifier que le message d'erreur est affiché sur la page après redirection
        $crawler = $client->getCrawler();
        $this->assertSelectorTextContains('.alert-danger', 'Une erreur est survenue !');

    }
}
