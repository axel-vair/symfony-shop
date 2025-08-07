<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class OrderService
{
    private EntityManagerInterface $entityManager;
    private CartService $cartService;


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
    public function createOrderFromCart(User $user): Order
    {
        $cart = $user->getCart();
        if (!$cart || $cart->getCartItems()->isEmpty()) {
            throw new \Exception('Le panier est vide');
        }

        $order = new Order();
        $order->setUtilisateur($user);
        $ulid = new Ulid();
        $order->setReference($ulid);
        $order->setTotal($this->cartService->calculateTotal($cart));
        $order->setStatus('En attente');
        $order->setCreatedAt(new \DateTimeImmutable());

        foreach ($cart->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);

            $product = $cartItem->getProduct();
            if(!$product){
                throw new \Exception('Produit non disponible');
            }
            $orderItem->setProduct($product);
            $orderItem->setQuantity((int)$cartItem->getQuantity());
            $orderItem->setPrice((float)$product->getPrice());
            $order->addOrderItem($orderItem);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}
