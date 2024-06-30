<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private $entityManager;
    private $cartService;

    public function __construct(EntityManagerInterface $entityManager, CartService $cartService)
    {
        $this->entityManager = $entityManager;
        $this->cartService = $cartService;
    }

    public function createOrderFromCart(User $user)
    {
        $cart = $user->getCart();
        if (!$cart || $cart->getCartItems()->isEmpty()) {
            throw new \Exception('Le panier est vide');
        }

        $order = new Order();
        $order->setUtilisateur($user);
        $order->setTotal($this->cartService->calculateTotal($cart));
        $order->setStatus('En attente');
        $order->setCreatedAt(new \DateTimeImmutable());

        foreach ($cart->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setPrice($cartItem->getProduct()->getPrice());
            $order->addOrderItem($orderItem);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Vider le panier après la création de la commande
        $this->cartService->removeFromCart($user);

        return $order;
    }

}