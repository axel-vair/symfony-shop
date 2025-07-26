<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class CategoryEntityTest extends TestCase
{
    public function testCategoryEntity()
    {
        // Création de l'entité Category
        $category = new Category();
        $category->setName('Electronics');

        // Vérification que le nom est bien défini
        $this->assertSame('Electronics', $category->getName());
    }

    public function testAddProductToCategory()
    {
        // Création des entités Category et Product
        $category = new Category();
        $category->setName('Electronics');

        $product = new Product();
        $product->setName('Smartphone');

        // Ajouter un produit à la catégorie
        $category->addProduct($product);

        // Vérification que la catégorie contient le produit
        $this->assertCount(1, $category->getProducts());
        $this->assertSame($category, $product->getCategory());
    }

    public function testRemoveProductFromCategory()
    {
        // Création des entités Category et Product
        $category = new Category();
        $category->setName('Electronics');

        $product = new Product();
        $product->setName('Smartphone');

        // Ajouter et ensuite supprimer un produit
        $category->addProduct($product);
        $category->removeProduct($product);

        // Vérification que la catégorie ne contient plus le produit
        $this->assertCount(0, $category->getProducts());
        $this->assertNull($product->getCategory());
    }

    public function testToStringMethod()
    {
        // Création de la catégorie sans nom
        $category = new Category();

        // Test de la méthode __toString()
        $this->assertSame('Nom inconnu', $category->__toString());

        // Définition du nom de la catégorie
        $category->setName('Electronics');

        // Test après avoir défini le nom
        $this->assertSame('Electronics', $category->__toString());
    }
}
