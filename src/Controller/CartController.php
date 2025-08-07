<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Service\CartService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CartController extends AbstractController
{
    const INVALID = 'Product invalide';

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
     * Crée la commande à partir du panier ET lance la session Stripe Checkout.
     */
    #[Route('/panier/caisse', name: 'app_cart_caisse', methods: ['POST'])]
    public function caisse(OrderService $orderService): Response
    {
        // Vérifie si l'utilisateur a le rôle 'ROLE_USER', sinon refuse l'accès
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();


        if (!$user instanceof User) {
            // Si l'utilisateur n'est pas authentifié
            $this->addFlash('error', 'Utilisateur non authentifié.');
            return $this->redirectToRoute('app_cart');
        }

        try {
            //crée la commande et la met en statut "en attente"
            $order = $orderService->createOrderFromCart($user);
            $this->addFlash('success', 'Votre commande a été initialisée avec succès.');
            return $this->redirectToRoute('app_cart_paiement', ['reference' => (string) $order->getReference()]);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_cart');
        }
    }

    /**
     * Récupère le contenu de la commande et instancie un session stripe
     * @param Order $order
     * @param UrlGeneratorInterface $urlGenerator
     * @return RedirectResponse
     * @throws \Stripe\Exception\ApiErrorException
     */
    #[Route('/panier/paiement/{reference}', name: 'app_cart_paiement')]
    public function paiement (Order $order,
                              UrlGeneratorInterface $urlGenerator,
    ): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        /** @var User $user */
        $user = $this->getUser();

        $stripe = new StripeClient('%env(STRIPE_SECRET)%');
        $referenceStr = (string) $order->getReference();

        $lineItems = [];
        foreach ($order->getOrderItems() as $orderItem) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int)($orderItem->getPrice() * 100),
                    'product_data' => [
                        'name' => $orderItem->getProduct()->getName(),
                    ],
                ],
                'quantity' => $orderItem->getQuantity(),
            ];
        }

        $session = $stripe->checkout->sessions->create([
            'customer_email' => $user->getEmail(),
            'line_items' => $lineItems,
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'success_url' => $urlGenerator->generate('app_succes', [
                'reference' => $referenceStr
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('app_cancel', [
                'reference' => $referenceStr
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'client_reference_id' => $referenceStr,
        ]);

        return new RedirectResponse($session->url);
    }


    /**
     * En cas de succès Stripe, on marque la commande "Payée" puis on redirige vers les détails de la commande.
     */
    #[Route('/panier/succes/{reference}', name: 'app_succes')]
    public function succes(Order $order,
                           EntityManagerInterface $entityManager,
                           CartService $cartService): Response
    {

        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('User not found or not authenticated');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($order->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès refusé à cette commande');
        }

        if ($order->getStatus() !== 'Payée') {
            $order->setStatus('Payée');
            $entityManager->flush();
        }

        // Vider le panier
        $cartService->removeFromCart($user);

        $this->addFlash('success', 'Paiement réussi, votre commande est finalisée.');

        return $this->redirectToRoute('app_order_details', [
            'reference' => $order->getReference(),
        ]);
    }

    /**
     * Annulation du paiement
     */
    #[Route('/panier/cancel/{reference}', name: 'app_cancel')]
    public function cancel(Order $order): Response
    {
        $this->addFlash('warning', 'La commande est en attente de paiement');

        return $this->redirectToRoute('app_order_details', [
            'reference' => $order->getReference(),
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
            $this->addFlash('error', self::INVALID);
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
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException('Vous devez être connecté');
        }

        // Vide le contenu du panier
        $cartService->removeFromCart($user);

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
            $this->addFlash('error', self::INVALID);
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
            $this->addFlash('error', self::INVALID);
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
            $this->addFlash('error', self::INVALID);
            return $this->redirectToRoute('app_cart');
        }

        // Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

}
