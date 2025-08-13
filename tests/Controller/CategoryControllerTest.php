<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;

class CategoryControllerTest extends WebTestCase
{
    public function testCategoryPageWithExistingCategory()
    {
        $client = static::createClient();
        $category = $client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy([]);
        if (!$category) {
            $this->markTestSkipped('Pas de catégorie en base pour tester.');
        }

        $name = $category->getName();
        $client->request('GET', '/categorie/'.$name);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $name);
    }

    public function testCategoryPageWithCaseInsensitiveSearch()
    {
        $client = static::createClient();
        $category = $client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy([]);
        if (!$category) {
            $this->markTestSkipped('Pas de catégorie en base pour tester.');
        }

        // Tester la recherche insensible à la casse
        $name = strtoupper($category->getName());
        $client->request('GET', '/categorie/'.$name);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $category->getName());
    }

    public function testCategoryPageNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/categorie/inconnue');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testCategoryFilterRedirect()
    {
        $client = static::createClient();
        $category = $client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy([]);
        if (!$category) {
            $this->markTestSkipped('Pas de catégorie en base pour tester.');
        }


        $client->request('GET', '/categorie/'.$category->getSlug());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $category->getName());

        $this->assertSelectorExists('.product-card');
    }

    public function testCategoryFilterInvalidCategory()
    {
        $client = static::createClient();

        // ID invalide (en dehors de la portée des IDs valides en base)
        $client->request('GET', '/category/9999999');
        $this->assertResponseStatusCodeSame(404);
    }
}
