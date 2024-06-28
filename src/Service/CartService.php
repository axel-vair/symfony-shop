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
    public function addToCard(int $id): void
    {
        $cart = $this->requestStack->getCurrentRequest()->get('cart', []);
        if (!empty($card[$id])) {
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $this->getSession()->set('cart', $cart);
    }

    public function getTotal(): array
    {
        $cart = $this->getSession()->get('cart', []);
        $cartData = [];
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->findOneBy(['id' => $id]);
            if(!$product){

            }
            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }
        return $cartData;
    }
    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}