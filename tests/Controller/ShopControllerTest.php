<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;

class ShopControllerTest extends WebTestCase
{
    public function testShopPageWithoutFilter()
    {
        $client = static::createClient();

        $client->request('GET', '/boutique');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.product-card');

        $this->assertSelectorExists('.category-link');
    }

    public function testShopPageWithCategoryFilter()
    {
        $client = static::createClient();

        $category = $client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy([]);
        if (!$category) {
            $this->markTestSkipped('Pas de catégorie en base pour tester.');
        }

        $client->request('GET', '/boutique?categorie=' . $category->getSlug());
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.list-group-item');

        $this->assertSelectorExists('.product-card');
    }
}
