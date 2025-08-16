<?php

namespace App\Tests\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;

class CategoryControllerTest extends WebTestCase
{
    public function testCategoryPageWithExistingCategory(): void
    {
        $client = static::createClient();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $client->getContainer()->get('doctrine');
        $category = $doctrine->getRepository(Category::class)->findOneBy([]);
        if (!$category) {
            $this->markTestSkipped('Pas de catégorie en base pour tester.');
        }

        $name = $category->getName();
        $this->assertNotNull($name, 'Le nom de la catégorie ne doit pas être null');

        $client->request('GET', '/categorie/' . $name);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $name);
    }

    public function testCategoryPageWithCaseInsensitiveSearch(): void
    {
        $client = static::createClient();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $client->getContainer()->get('doctrine');
        $category = $doctrine->getRepository(Category::class)->findOneBy([]);
        if (!$category) {
            $this->markTestSkipped('Pas de catégorie en base pour tester.');
        }

        $name = $category->getName();
        $this->assertNotNull($name, 'Le nom de la catégorie ne doit pas être null');

        $nameUpper = strtoupper($name);

        $client->request('GET', '/categorie/' . $nameUpper);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $name);
    }

    public function testCategoryPageNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/categorie/inconnue');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testCategoryFilterRedirect(): void
    {
        $client = static::createClient();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $client->getContainer()->get('doctrine');
        $category = $doctrine->getRepository(Category::class)->findOneBy([]);
        if (!$category) {
            $this->markTestSkipped('Pas de catégorie en base pour tester.');
        }

        $name = $category->getName();
        $this->assertNotNull($name, 'Le nom de la catégorie ne doit pas être null');

        $client->request('GET', '/categorie/' . $category->getSlug());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $name);

        $this->assertSelectorExists('.product-card');
    }


    public function testCategoryFilterInvalidCategory(): void
    {
        $client = static::createClient();

        // ID invalide (en dehors de la portée des IDs valides en base)
        $client->request('GET', '/category/9999999');
        $this->assertResponseStatusCodeSame(404);
    }
}
