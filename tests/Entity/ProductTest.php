<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testDefault()
    {
        // Création d'une nouvelle instance de Product
        $product = new Product();
        
        // Utilisation des setters pour initialiser les valeurs
        $product->setName('Nametest');
        $product->setStock(5);
        $product->setPrice(80.0);
        $product->setImage('/image.jpg');
        $product->setCreatedDate(new \DateTime('2024-02-09'));
        $product->setDescription('description product test');
        $product->setComment('comment test');

        // Assertions pour vérifier que les valeurs sont correctes
        $this->assertEquals('Nametest', $product->getName());
        $this->assertEquals(5, $product->getStock());
        $this->assertEquals(new \DateTime('2024-02-09'), $product->getCreatedDate());

    }
}