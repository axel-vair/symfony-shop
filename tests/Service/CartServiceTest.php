<?php

namespace App\Tests\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Common\Collections\ArrayCollection;

class CartServiceTest extends TestCase
{
    public function testAddToCartNewProduct(): void
    {
        $productId = 123;

        // Créer un produit factice
        $product = new Product();
        $reflection = new \ReflectionClass($product);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($product, $productId);
        $product->setPrice(10.0);

        // Mock du ProductRepository pour retourner le produit sur find()
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn($product);

        // Mock du Security pour retourner un utilisateur avec un panier vide
        $cart = new Cart();
        $user = $this->createMock(User::class);
        $user->method('getCart')->willReturn($cart);
        $user->method('setCart')->willReturnSelf();


        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        // Mock de l'EntityManager
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($cart);
        $entityManager->expects($this->once())->method('flush');

        // Instanciation du service avec les mocks
        $cartService = new CartService($productRepository, $security, $entityManager);

        // Appel de la méthode à tester
        $cartService->addToCart($productId);

        // Assert que le panier contient le produit ajouté avec quantité 1
        $this->assertCount(1, $cart->getCartItems());
        $cartItem = $cart->getCartItems()->first();
        $this->assertInstanceOf(CartItem::class, $cartItem);
        $this->assertEquals($product, $cartItem->getProduct());
        $this->assertEquals(1, $cartItem->getQuantity());
    }
}
