<?php 

namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/*La classe WebTestCase de Symfony est une classe de base dédiée 
aux tests fonctionnels dans les applications Symfony. 
Elle est conçue pour simuler des requêtes HTTP et pour tester 
le comportement de l'application dans des conditions proches de celles rencontrées en production.*/

class ContactControllerTest extends WebTestCase
{
    public function testContactFormSubmissionSendsFlashMessage()
    {
        $client = static::createClient();

        // Simule une requête GET sur la page de contact pour récupérer le formulaire
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();

        // Pour debugger -> permet de voir le contentu html
        // echo $crawler->html(); 

        $this->assertSelectorExists('form'); 
        $form = $crawler->selectButton('Envoyer le message')->form([
            'contact[email]' => 'tegst2@exale.com',
            'contact[name]' => 'Jule Dodo',
            'contact[message]' => 'This is a test h message.',
        ]);
        
        $client->submit($form);

        //pour eviter l'erreure 500 il faut créer une base de données test
        if ($client->getResponse()->getStatusCode() === 500) {
            echo $client->getResponse()->getContent();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        // Vérifie si un message flash, on cible avec la classe du container
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('.alert.alert-success', 'Votre message a été envoyé !');

    }
}