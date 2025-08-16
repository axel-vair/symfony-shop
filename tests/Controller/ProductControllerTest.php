<?php

namespace App\Tests\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Product;

class ProductControllerTest extends WebTestCase
{
    public function testProductPageWithValidProduct(): void
    {
        $client = static::createClient();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $client->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $product = $em->getRepository(Product::class)->findOneBy([]);
        if (!$product) {
            $this->markTestSkipped('Pas de produit en base pour tester.');
        }

        $slug = $product->getSlug();
        $this->assertNotNull($slug, 'Le slug du produit ne doit pas être nul');

        $client->request('GET', '/produit/' . $slug);
        $this->assertResponseIsSuccessful();

        $name = $product->getName();
        $this->assertNotNull($name, 'Le nom du produit ne doit pas être nul');
        $this->assertSelectorTextContains('h1', $name);
    }

    public function testProductPageNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/produit/99999999');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testProductPageCaseInsensitiveSlug(): void
    {
        $client = static::createClient();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $client->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $product = $em->getRepository(Product::class)->findOneBy([]);
        if (!$product) {
            $this->markTestSkipped('Pas de produit en base pour tester.');
        }

        $slug = $product->getSlug();
        $this->assertNotNull($slug, 'Le slug du produit ne doit pas être nul');

        $mixedCaseSlug = strtoupper($slug);

        $client->request('GET', '/produit/' . $mixedCaseSlug);
        $this->assertResponseIsSuccessful();

        $name = $product->getName();
        $this->assertNotNull($name, 'Le nom du produit ne doit pas être nul');
        $this->assertSelectorTextContains('h1', $name);
    }
}
