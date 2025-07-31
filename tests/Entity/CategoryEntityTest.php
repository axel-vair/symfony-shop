<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class CategoryEntityTest extends TestCase
{
    public function testCategoryEntity()
    {
        $category = new Category();
        $category->setName('Tapis');

        $this->assertSame('Tapis', $category->getName());
    }

    public function testAddProductToCategory()
    {
        $category = new Category();
        $category->setName('Tapis');

        $product = new Product();
        $product->setName('Tapis Yoga');

        $category->addProduct($product);

        $this->assertCount(1, $category->getProducts());
        $this->assertSame($category, $product->getCategory());
    }

    public function testRemoveProductFromCategory()
    {
        $category = new Category();
        $category->setName('Tapis');

        $product = new Product();
        $product->setName('Tapis Yoga');

        $category->addProduct($product);
        $category->removeProduct($product);

        $this->assertCount(0, $category->getProducts());
        $this->assertNull($product->getCategory());
    }

    public function testToStringMethod()
    {
        $category = new Category();

        // Test de la méthode __toString()
        $this->assertSame('Nom inconnu', $category->__toString());

        $category->setName('Tapis');

        // Test après avoir défini le nom
        $this->assertSame('Tapis', $category->__toString());
    }
}
