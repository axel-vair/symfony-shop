<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    /**
     * Affiche les détails d'un produit spécifique.
     *
     * Cette méthode utilise le ParamConverter de Symfony pour automatiquement
     * récupérer l'entité Product correspondant à l'ID fourni dans l'URL.
     * Elle rend ensuite une vue avec les détails de ce produit.
     *
     * @param Product $product L'entité Product récupérée automatiquement par l'ID
     * @return Response La réponse HTTP contenant la vue des détails du produit
     *
     * @Route("/product/{id}", name="app_product_id", methods={"GET"})
     */
    #[Route('/product/{id}', name: 'app_product_id', methods: ['GET'])]
    public function index(Product $product): Response
    {
        // Rend la vue des détails du produit en passant l'objet Product
        return $this->render('pages/product/show.html.twig', [
            "product" => $product,
        ]);
    }
}