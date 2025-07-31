<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Product;

class ProductControllerTest extends WebTestCase
{
    public function testProductPageWithValidProduct()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getManager();

        $product = $em->getRepository(Product::class)->findOneBy([]);
        if (!$product) {
            $this->markTestSkipped('Pas de produit en base pour tester.');
        }

        $client->request('GET', '/product/'.$product->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $product->getName() ?? '');
    }

    public function testProductPageNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/product/99999999');
        $this->assertResponseStatusCodeSame(404);
    }
}
