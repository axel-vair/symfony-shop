<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testAccueilAfficheCategoriesEtProduits()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        // On vérifie quelques éléments de la page (titre, boutons, etc.)
        $this->assertSelectorTextContains('h1', 'Bienvenue chez Butterfly');
        $this->assertSelectorExists('.category-card');
        $this->assertSelectorExists('.card-img-top');

        // Vérifier si les catégories sont listées (prend la première catégorie fictive si tu veux)
        $this->assertGreaterThan(0, $crawler->filter('.category-card')->count());

        // Vérifier si des produits sont listés
        $this->assertGreaterThan(0, $crawler->filter('.card-img-top')->count());

        // Vérifie un lien produit
        $this->assertSelectorExists('a[href^="/product/"]');
    }
}
