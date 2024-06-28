<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('pages/cart/index.html.twig', [
            'cart' => $cartService->getCartContents(),
            'total' => $cartService->calculateTotal()

        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_cart_add')]
    public function addToRoute(CartService $cartService, Product $product): Response
    {
        $cartService->addToCart($product->getId());
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/delete', name: 'app_cart_delete')]
    public function deleteCart(CartService $cartService): Response
    {
        $cartService->removeFromCart();
        return $this->redirectToRoute('app_shop');
    }
}
