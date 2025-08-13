<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;

class CartService
{
    private ProductRepository $productRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ProductRepository $productRepository,
        Security $security,
        EntityManagerInterface $entityManager
    ) {
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
     * @throws Exception
     */
    public function addToCart(int $id): void
    {
        $cart = $this->getCart();
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($id) {
            $product = $item->getProduct();
            return $product && $product->getId() === $id;
        })->first();

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $product = $this->productRepository->find($id);

            $cartItem = new CartItem();
            $cartItem->setProduct($product);
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
     */
    public function removeOneProductToCart(int $productId): void
    {
        $cart = $this->getCart();
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($productId) {
            $product = $item->getProduct();
            return $product && $product->getId() === $productId;
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
     */
    public function removeFromCart(): void
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
        $total = 0.0;
        foreach ($cart->getCartItems() as $cartItem) {
            $product = $cartItem->getProduct();
            if ($product) {
                $total += $product->getPrice() * $cartItem->getQuantity();
            }
        }
        return $total;
    }

    /**
     * Augmente la quantité d'un produit dans le panier.
     *
     * @param int $id L'ID du produit à augmenter
     */
    public function increaseQuantity(int $id): void
    {
        $cart = $this->getCart();
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($id) {
            $product = $item->getProduct();
            return $product && $product->getId() === $id;
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
     */
    public function decreaseQuantity(int $id): void
    {
        $cart = $this->getCart();
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($id) {
            $product = $item->getProduct();
            return $product && $product->getId() === $id;
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

    /**
     * Retourne le panier de l'utilisateur connecté.
     *
     * @return Cart
     */
    public function getCart(): Cart
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new LogicException('User must be logged in to access cart.');
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

    /**
     * Retourne le nombre total d'items dans le panier.
     *
     * @return int
     */
    public function getCartItemCount(): int
    {
        $cart = $this->getCart();
        return $cart->getCartItems()->count();
    }
}
