<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    public function __construct(RequestStack $requestStack, ProductRepository $productRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * Ajoute un produit au panier.
     * Si le produit est déjà dans le panier, on incrémente la quantité.
     * Sinon, on ajoute le produit avec une quantité de 1.
     *
     * @param int $id L'ID du produit à ajouter
     * @return void
     */
    public function addToCart(int $id): void
    {
        $cart = $this->getCart();
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($id) {
            return $item->getProduct()->getId() === $id;
        })->first();

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $cartItem = new CartItem();
            $cartItem->setProduct($this->productRepository->find($id));
            $cartItem->setQuantity(1);
            $cart->addCartItem($cartItem);
        }

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    /**
     * Supprime un produit spécifique du panier.
     *
     * @param int $productId L'ID du produit à supprimer
     * @return void
     */
    public function removeOneProductToCart(int $productId)
    {
        $cart = $this->getCart();
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($productId) {
            return $item->getProduct()->getId() === $productId;
        })->first();
        if ($cartItem) {
            $cart->removeCartItem($cartItem);
            $this->entityManager->remove($cartItem);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }
    }

    /**
     * Supprime le contenu du panier.
     *
     * @return void
     */
    public function removeFromCart()
    {
        $cart = $this->getCart();
        foreach ($cart->getCartItems() as $cartItem) {
            $this->entityManager->remove($cartItem);
        }
        $cart->getCartItems()->clear();
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    /**
     * Calcule le montant total du panier.
     *
     * @param Cart $cart Le panier dont on veut calculer le total
     * @return float
     */
    public function calculateTotal(Cart $cart): float
    {
        $total = 0;
        foreach ($cart->getCartItems() as $cartItem) {
            $total += $cartItem->getProduct()->getPrice() * $cartItem->getQuantity();
        }
        return $total;
    }

    /**
     * Augmente la quantité d'un produit dans le panier.
     *
     * @param int $id L'ID du produit à augmenter
     * @return void
     */
    public function increaseQuantity(int $id): void
    {
        $cart = $this->getCart();
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($id) {
            return $item->getProduct()->getId() === $id;
        })->first();
        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
            $this->entityManager->persist($cartItem);
            $this->entityManager->flush();
        }
    }

    /**
     * Diminue la quantité d'un produit dans le panier.
     * Si la quantité est de 1, le produit est supprimé du panier.
     *
     * @param int $id L'ID du produit à diminuer
     * @return void
     */
    public function decreaseQuantity(int $id): void
    {
        $cart = $this->getCart();
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($id) {
            return $item->getProduct()->getId() === $id;
        })->first();
        if ($cartItem) {
            if ($cartItem->getQuantity() > 1) {
                $cartItem->setQuantity($cartItem->getQuantity() - 1);
                $this->entityManager->persist($cartItem);
            } else {
                $cart->removeCartItem($cartItem);
                $this->entityManager->remove($cartItem);
            }
            $this->entityManager->flush();
        }
    }

    public function getCart(): Cart
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \LogicException('User must be logged in to access cart.');
        }

        $cart = $user->getCart();
        if (!$cart) {
            $cart = new Cart();
            $cart->setUtilisateur($user);
            $user->setCart($cart);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return $cart;
    }

    public function getCartItemCount(): int
    {
        $cart = $this->getCart();
        return $cart->getCartItems()->count();
    }
}