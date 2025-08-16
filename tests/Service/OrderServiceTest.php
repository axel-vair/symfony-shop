<?php

namespace App\Tests\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Service\CartService;
use App\Service\OrderService;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    public function testCreateOrderFromCart(): void
    {
        // Création d’un produit factice avec prix
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn(20.0);

        // Création d’un cartItem factice retournant le produit et une quantité
        $cartItem = $this->createMock(CartItem::class);
        $cartItem->method('getProduct')->willReturn($product);
        $cartItem->method('getQuantity')->willReturn(2);

        // Collection contenant l’item de panier
        $cartItemsCollection = new ArrayCollection([$cartItem]);

        // Création du panier avec la collection d’items
        $cart = $this->createMock(Cart::class);
        $cart->method('getCartItems')->willReturn($cartItemsCollection);

        // Création d’un utilisateur retournant ce panier
        $user = $this->createMock(User::class);
        $user->method('getCart')->willReturn($cart);

        // Mock du service CartService avec calcul total connu
        $cartService = $this->createMock(CartService::class);
        $cartService->expects($this->once())
            ->method('calculateTotal')
            ->with($cart)
            ->willReturn(40.0);

        // Mock de l’EntityManager pour vérifier persist et flush
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Order::class));
        $entityManager->expects($this->once())->method('flush');

        // Création de l’instance du service à tester
        $orderService = new OrderService($entityManager, $cartService);

        // Exécution de la méthode testée
        $order = $orderService->createOrderFromCart($user);

        // Vérifications sur l’objet retourné
        $this->assertInstanceOf(Order::class, $order);
        $this->assertSame($user, $order->getUtilisateur());
        $this->assertEquals(40.0, $order->getTotal());
        $this->assertEquals('En attente', $order->getStatus());
        $this->assertInstanceOf(DateTimeImmutable::class, $order->getCreatedAt());

        // Vérifier collection des OrderItems
        $orderItems = $order->getOrderItems();
        $this->assertGreaterThan(0, $orderItems->count());

        $orderItem = $orderItems->first();
        $this->assertNotFalse($orderItem);
        $this->assertSame($product, $orderItem->getProduct());
        $this->assertEquals(2, $orderItem->getQuantity());
        $this->assertEquals(20.0, $orderItem->getPrice());
    }
}
