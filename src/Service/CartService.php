<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    /**
     * Add a product in our cart.
     * @param int $id
     * @return void
     */
    public function addToCart(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $this->getSession()->set('cart', $cart);
    }

    public function removeOneProductToCart(int $productId)
    {
        $cart = $this->getSession()->get('cart', []);
        unset($cart[$productId]);
        $this->getSession()->set('cart', $cart);
    }
    public function removeFromCart()
    {
        return $this->getSession()->remove('cart');
    }
    public function getCartContents(): array
    {
        $cart = $this->getSession()->get('cart', []);
        $cartData = [];
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->findOneBy(['id' => $id]);
            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }
        return $cartData;
    }

    public function calculateTotal(): float
    {
        $total = 0;
        $cartContents = $this->getCartContents();
        foreach ($cartContents as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    public function increaseQuantity(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        }
        $this->getSession()->set('cart', $cart);
    }

    public function decreaseQuantity(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id]) && $cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }
        $this->getSession()->set('cart', $cart);
    }
    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}