<?php

namespace App\Tests\E2E;


use Symfony\Component\Panther\PantherTestCase;

/**
 * @group panther
 */
class HomepageTest extends PantherTestCase
{
    public function testHomepageIsSuccessful(): void
    {
        $client = self::createPantherClient(['browser' => PantherTestCase::FIREFOX]);
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Bienvenue chez Butterfly');
    }
}
