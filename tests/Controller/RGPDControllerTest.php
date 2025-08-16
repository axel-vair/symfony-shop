<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RGPDControllerTest extends WebTestCase
{
    public function testAffichageRGPD(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/mention-legales');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Politique de Confidentialité');
    }
}
