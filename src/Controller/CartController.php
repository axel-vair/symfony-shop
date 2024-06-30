<?php
namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CartController extends AbstractController
{
    /**
     * Affiche la page du panier.
     * Seuls les utilisateurs authentifiés peuvent accéder à cette route.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @return Response
     */
/*    #[IsGranted("ROLE_USER")]
    #[Route('/panier', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('pages/cart/index.html.twig', [
            'cart' => $cartService->getCartContents(), // Récupère le contenu du panier
            'total' => $cartService->calculateTotal() // Calcule le montant total du panier
        ]);
    }*/

    #[Route('/panier', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cart = $cartService->getCart();
        $total = $cartService->calculateTotal($cart);

        return $this->render('pages/cart/index.html.twig', [
            'cart' => $cart->getCartItems()->toArray(),
            'total' => $total,
        ]);
    }

    /**
     * Ajoute un produit au panier.
     * Redirige vers la page du panier après l'ajout.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @param Product $product Le produit à ajouter au panier
     * @return Response
     */
/*    #[Route('/panier/add/{id}', name: 'app_cart_add')]
    public function addToRoute(CartService $cartService, Product $product): Response
    {
        $cartService->addToCart($product->getId()); // Ajoute le produit au panier
        return $this->redirectToRoute('app_cart'); // Redirige vers la page du panier
    }*/

    #[Route('/panier/add/{id}', name: 'app_cart_add')]
    public function addToRoute(CartService $cartService, Product $product): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cartService->addToCart($product->getId());
        return $this->redirectToRoute('app_cart');
    }
    /**
     * Vide le contenu du panier.
     * Affiche un message de succès et redirige vers la page du panier.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @return Response
     */
    #[Route('/panier/vider', name: 'app_cart_delete')]
    public function deleteCart(CartService $cartService): Response
    {
        $cartService->removeFromCart(); // Vide le contenu du panier
        $this->addFlash('success', 'Votre panier a été vidé.'); // Ajoute un message flash de succès
        return $this->redirectToRoute('app_cart'); // Redirige vers la page du panier
    }

    /**
     * Augmente la quantité d'un produit dans le panier.
     * Redirige vers la page du panier après l'augmentation.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @param Product $product Le produit dont on augmente la quantité
     * @return Response
     */
    #[Route('/panier/quantity/increase/{id}', name: 'app_cart_quantity_increase')]
    public function increaseQuantity(CartService $cartService, Product $product)
    {
        $cartService->increaseQuantity($product->getId()); // Augmente la quantité du produit
        return $this->redirectToRoute('app_cart'); // Redirige vers la page du panier
    }

    /**
     * Diminue la quantité d'un produit dans le panier.
     * Redirige vers la page du panier après la diminution.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @param Product $product Le produit dont on diminue la quantité
     * @return Response
     */
    #[Route('/panier/quantity/decrease/{id}', name: 'app_cart_quantity_decrease')]
    public function decreaseQuantity(CartService $cartService, Product $product)
    {
        $cartService->decreaseQuantity($product->getId()); // Diminue la quantité du produit
        return $this->redirectToRoute('app_cart'); // Redirige vers la page du panier
    }

    /**
     * Supprime un produit spécifique du panier.
     * Redirige vers la page du panier après la suppression.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @param Product $product Le produit à supprimer du panier
     * @return Response
     */
    #[Route('/panier/delete/product/{id}', name: 'app_cart_one_product_delete')]
    public function deleteOneProductFromCart(CartService $cartService, Product $product)
    {
        $cartService->removeOneProductToCart($product->getId()); // Supprime le produit du panier
        return $this->redirectToRoute('app_cart'); // Redirige vers la page du panier
    }

    /**
     * @param OrderService $orderService
     * @return Response
     */
    #[Route('/panier/valider', name: 'app_cart_validate')]
    public function validateCart(OrderService $orderService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            $order = $orderService->createOrderFromCart($this->getUser());
            $this->addFlash('success', 'Votre commande a été créée avec succès.');
            return $this->redirectToRoute('app_user_orders');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_cart');
        }
    }

}
