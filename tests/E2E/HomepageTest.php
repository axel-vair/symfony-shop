<?php

namespace App\Tests\E2E;


use Symfony\Component\Panther\PantherTestCase;

class HomepageTest extends PantherTestCase
{
    public function testHomepageIsSuccessful(): void
    {
        $client = self::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Bienvenue chez Butterfly');
    }
}
