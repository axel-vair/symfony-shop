<?php

namespace App\Twig;

use App\Service\CartService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Définit les fonctions Twig disponibles dans ce contexte.
     *
     * Cette méthode retourne un tableau de fonctions Twig, chaque fonction étant une instance de TwigFunction.
     * Dans ce cas, nous définissons une fonction nommée 'cart_count' qui appelle la méthode getCartCount.
     *
     * @return array<\Twig\TwigFunction> Un tableau de fonctions Twig
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('cart_count', [$this, 'getCartCount']),
        ];
    }

    /**
     * Retourne le nombre d'éléments dans le panier de l'utilisateur.
     *
     * Cette méthode utilise le CartService pour récupérer le nombre d'éléments dans le panier.
     * Elle est appelée par la fonction Twig 'cart_count'.
     *
     * @return int Le nombre d'éléments dans le panier
     */
    public function getCartCount(): int
    {
        return $this->cartService->getCartItemCount();
    }
}
