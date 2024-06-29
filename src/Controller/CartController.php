<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CartController extends AbstractController
{
    #[IsGranted("ROLE_USER")]
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

    #[Route('/panier/vider', name: 'app_cart_delete')]
    public function deleteCart(CartService $cartService): Response
    {
        $cartService->removeFromCart();
        $this->addFlash('success', 'Votre panier a été vidé.');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/quantity/increase/{id}', name: 'app_cart_quantity_increase')]
    public function increaseQuantity(CartService $cartService, Product $product)
    {
        $cartService->increaseQuantity($product->getId());
        return $this->redirectToRoute('app_cart');

    }

    #[Route('/panier/quantity/decrease/{id}', name: 'app_cart_quantity_decrease')]
    public function decreaseQuantity(CartService $cartService, Product $product)
    {
        $cartService->decreaseQuantity($product->getId());
        return $this->redirectToRoute('app_cart');

    }

    #[Route('/panier/delete/product/{id}', name: 'app_cart_one_product_delete')]
    public function deleteOneProductFromCart(CartService $cartService, Product $product)
    {
        $cartService->removeOneProductToCart($product->getId());
        return $this->redirectToRoute('app_cart');
    }

}
