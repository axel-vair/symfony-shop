<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Service\CartService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    /**
     * Affiche la page du panier.
     * Cette fonction vérifie d'abord si l'utilisateur est authentifié avec le rôle 'ROLE_USER'.
     * Elle récupère ensuite le contenu du panier et calcule le total via le CartService.
     * Enfin, elle rend la vue du panier avec ces informations.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @return Response La réponse HTTP contenant la vue du panier
     */
    #[Route('/panier', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        // Vérifie si l'utilisateur a le rôle 'ROLE_USER', sinon refuse l'accès
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Récupère le panier et calcule le total
        $cart = $cartService->getCart();
        $total = $cartService->calculateTotal($cart);

        // Rend la vue du panier avec les données du panier et le total
        return $this->render('pages/cart/index.html.twig', [
            'cart' => $cart->getCartItems()->toArray(),
            'total' => $total,
        ]);
    }

    /**
     * Ajoute un produit au panier.
     * Cette fonction vérifie d'abord si l'utilisateur est authentifié.
     * Elle ajoute ensuite le produit au panier via le CartService.
     * Enfin, elle redirige l'utilisateur vers la page du panier.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @param Product $product Le produit à ajouter au panier
     * @return Response Une redirection vers la page du panier
     */
    #[Route('/panier/add/{id}', name: 'app_cart_add')]
    public function addToRoute(CartService $cartService, Product $product): Response
    {
        // Vérifie si l'utilisateur a le rôle 'ROLE_USER', sinon refuse l'accès
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Ajoute le produit au panier
        $id = $product->getId();
        if (is_int($id)) {
            $cartService->addToCart($id);
        } else {
            // Gérer l'erreur ou rediriger l'utilisateur
            $this->addFlash('error', 'Produit invalide.');
            return $this->redirectToRoute('app_cart');
        }
        // Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    /**
     * Vide le contenu du panier.
     * Cette fonction supprime tous les produits du panier via le CartService.
     * Elle ajoute ensuite un message flash pour informer l'utilisateur.
     * Enfin, elle redirige l'utilisateur vers la page du panier.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @return Response Une redirection vers la page du panier
     */
    #[Route('/panier/vider', name: 'app_cart_delete')]
    public function deleteCart(CartService $cartService): Response
    {
        // Vide le contenu du panier
        $cartService->removeFromCart();

        // Ajoute un message flash de succès
        $this->addFlash('success', 'Votre panier a été vidé.');

        // Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    /**
     * Augmente la quantité d'un produit dans le panier.
     * Cette fonction utilise le CartService pour augmenter la quantité du produit spécifié.
     * Elle redirige ensuite l'utilisateur vers la page du panier.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @param Product $product Le produit dont on augmente la quantité
     * @return Response Une redirection vers la page du panier
     */
    #[Route('/panier/quantity/increase/{id}', name: 'app_cart_quantity_increase')]
    public function increaseQuantity(CartService $cartService, Product $product)
    {
        $id = $product->getId();

        if (is_int($id)) {
            // Augmente la quantité du produit dans le panier
            $cartService->increaseQuantity($id);
        } else {
            $this->addFlash('error', 'Produit invalide.');
            return $this->redirectToRoute('app_cart');
        }

        // Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    /**
     * Diminue la quantité d'un produit dans le panier.
     * Cette fonction utilise le CartService pour diminuer la quantité du produit spécifié.
     * Elle redirige ensuite l'utilisateur vers la page du panier.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @param Product $product Le produit dont on diminue la quantité
     * @return Response Une redirection vers la page du panier
     */
    #[Route('/panier/quantity/decrease/{id}', name: 'app_cart_quantity_decrease')]
    public function decreaseQuantity(CartService $cartService, Product $product)
    {
        $id = $product->getId();

        if (is_int($id)) {
            // Diminue la quantité du produit dans le panier
            $cartService->decreaseQuantity($id);
        } else {
            $this->addFlash('error', 'Produit invalide.');
            return $this->redirectToRoute('app_cart');
        }

        // Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    /**
     * Supprime un produit spécifique du panier.
     * Cette fonction utilise le CartService pour supprimer le produit spécifié du panier.
     * Elle redirige ensuite l'utilisateur vers la page du panier.
     *
     * @param CartService $cartService Le service de gestion du panier
     * @param Product $product Le produit à supprimer du panier
     * @return Response Une redirection vers la page du panier
     */
    #[Route('/panier/delete/product/{id}', name: 'app_cart_one_product_delete')]
    public function deleteOneProductFromCart(CartService $cartService, Product $product)
    {
        $id = $product->getId();

        if (is_int($id)) {
            // Supprime le produit spécifique du panier
            $cartService->removeOneProductToCart($id);
        } else {
            $this->addFlash('error', 'Produit invalide.');
            return $this->redirectToRoute('app_cart');
        }

        // Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    /**
     * Valide le panier et crée une commande.
     * Cette fonction vérifie d'abord si l'utilisateur est authentifié.
     * Elle tente ensuite de créer une commande à partir du panier via le OrderService.
     * En cas de succès, elle ajoute un message flash et redirige vers la page des commandes de l'utilisateur.
     * En cas d'échec, elle ajoute un message d'erreur et redirige vers la page du panier.
     *
     * @param OrderService $orderService Le service de gestion des commandes
     * @return Response Une redirection vers la page des commandes ou du panier
     */
    #[Route('/panier/valider', name: 'app_cart_validate')]
    public function validateCart(OrderService $orderService): Response
    {
        // Vérifie si l'utilisateur a le rôle 'ROLE_USER', sinon refuse l'accès
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        if ($user instanceof User) {
            try {
                // Tente de créer une commande à partir du panier
                $order = $orderService->createOrderFromCart($this->getUser());

                // Ajoute un message flash de succès
                $this->addFlash('success', 'Votre commande a été créée avec succès.');

                // Redirige vers la page des commandes de l'utilisateur
                return $this->redirectToRoute('app_user_orders');
            } catch (\Exception $e) {
                // En cas d'erreur, ajoute un message flash d'erreur
                $this->addFlash('error', $e->getMessage());

                // Redirige vers la page du panier
                return $this->redirectToRoute('app_cart');
            }
        } else {
            $this->addFlash('error', 'Utilisateur non authentifié.');
            return $this->redirectToRoute('app_cart');
        }
    }
}
