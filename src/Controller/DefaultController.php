<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    /**
     * Display every category and products into the homepage
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     * @return Response
     */
    #[Route('/', name: 'app_default')]
    public function index(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ): Response
    {

        // Récupère toutes les catégories de la base de données
        $categories = $categoryRepository->findAll();

        // Récupère tous les produits de la base de données
        $products = $productRepository->findAll();

        // Rend la vue de la page d'accueil en passant les catégories et les produits
        return $this->render('pages/default/index.html.twig', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
