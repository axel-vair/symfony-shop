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

    /**
     * Crée une commande à partir du panier de l'utilisateur.
     *
     * Cette méthode récupère le panier de l'utilisateur, calcule le total,
     * crée une nouvelle commande et ses éléments, puis enregistre la commande.
     * Enfin, elle vide le panier de l'utilisateur.
     *
     * @param User $user L'utilisateur pour lequel créer la commande
     * @return Order La commande créée
     * @throws \Exception Si le panier est vide
     */
    public function createOrderFromCart(User $user)
    {
        $cart = $user->getCart();
        if (!$cart || $cart->getCartItems()->isEmpty()) {
            throw new \Exception('Le panier est vide');
        }

        // Crée une nouvelle commande
        $order = new Order();
        $order->setUtilisateur($user);
        $order->setTotal($this->cartService->calculateTotal($cart));
        $order->setStatus('En attente');
        $order->setCreatedAt(new \DateTimeImmutable());

        // Crée les éléments de commande à partir des éléments du panier
        foreach ($cart->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setPrice($cartItem->getProduct()->getPrice());
            $order->addOrderItem($orderItem);
        }

        // Enregistre la commande
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Vide le panier après la création de la commande
        $this->cartService->removeFromCart($user);

        return $order;
    }
}