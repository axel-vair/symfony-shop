<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    /**
     * Affiche la page d'accueil avec toutes les catégories et tous les produits.
     *
     * Cette méthode récupère toutes les catégories et tous les produits de la base de données
     * et les passe à la vue pour affichage. Elle sert de point d'entrée principal pour l'application.
     *
     * @param CategoryRepository $categoryRepository Le repository pour accéder aux données des catégories
     * @param ProductRepository $productRepository Le repository pour accéder aux données des produits
     * @return Response La réponse HTTP contenant la vue de la page d'accueil
     */
    #[Route('/', name: 'app_default')]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
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