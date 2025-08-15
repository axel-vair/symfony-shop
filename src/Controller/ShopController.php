<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    /**
     * Affiche la page principale de la boutique avec une liste paginée de produits.
     *
     * Cette méthode récupère les produits (éventuellement filtrés par catégorie) et les affiche
     * de manière paginée. Elle récupère également toutes les catégories pour permettre le filtrage.
     *
     * @param ProductRepository $productRepository Repository pour accéder aux données des produits
     * @param CategoryRepository $categoryRepository Repository pour accéder aux données des catégories
     * @param int $page Numéro de la page courante (par défaut 1)
     * @return Response La réponse HTTP contenant la vue de la page de la boutique
     */
    #[Route('/boutique', name: 'app_shop')]
    public function index(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter(name: 'categorie')] ?string $categorySlug = null
    ): Response {
        // Crée un QueryBuilder pour construire dynamiquement la requête DQL
        $queryBuilder = $productRepository->createQueryBuilder('p');

        // Si un ID de catégorie est fourni, filtre les produits par cette catégorie
        if ($categorySlug) {
            $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);
            $queryBuilder->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        // Récupère toutes les catégories pour le menu de filtrage
        $categories = $categoryRepository->findAll();

        // Crée un adaptateur pour Pagerfanta basé sur le QueryBuilder
        $adapter = new QueryAdapter($queryBuilder);

        // Crée un objet Pagerfanta pour gérer la pagination
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $page,
            6  // Nombre de produits par page
        );

        // Rend la vue de la boutique avec les produits paginés, les catégories et la catégorie courante
        return $this->render('pages/shop/index.html.twig', [
            'products' => $pager,
            'categories' => $categories,
            'current_categorySlug' => $categorySlug,
        ]);
    }
}
