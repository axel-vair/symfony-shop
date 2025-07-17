<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    /**
     * Affiche la page d'une catégorie spécifique.
     *
     * Cette méthode recherche une catégorie par son nom. Si la recherche initiale échoue,
     * elle effectue une recherche insensible à la casse. Si aucune catégorie n'est trouvée,
     * elle lance une exception NotFoundException.
     *
     * @param string $name Le nom de la catégorie à afficher
     * @param CategoryRepository $categoryRepository Le repository pour accéder aux données des catégories
     * @return Response La réponse HTTP contenant la vue de la catégorie
     * @throws NotFoundHttpException Si la catégorie n'existe pas
     */
    #[Route('/categorie/{name}', name: 'app_category_index')]
    public function index(string $name, CategoryRepository $categoryRepository): Response
    {
        // Recherche la catégorie par son nom exact
        $category = $categoryRepository->findOneBy(['name' => $name]);

        if (!$category) {
            // Si la première recherche échoue, effectue une recherche insensible à la casse
            $category = $categoryRepository->createQueryBuilder('c')
                ->where('LOWER(c.name) = LOWER(:name)')
                ->setParameter('name', $name)
                ->getQuery()
                ->getOneOrNullResult();
        }

        if (!$category) {
            // Si aucune catégorie n'est trouvée, lance une exception NotFoundException
            throw $this->createNotFoundException('La catégorie demandée n\'existe pas');
        }

        // Rend la vue de la catégorie avec les données de la catégorie trouvée
        return $this->render('pages/category/index.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * Redirige vers la page de la boutique avec un filtre de catégorie.
     *
     * Cette méthode prend une catégorie en paramètre et redirige l'utilisateur
     * vers la page de la boutique en ajoutant l'ID de la catégorie comme paramètre de filtre.
     *
     * @param Category $category La catégorie à utiliser comme filtre
     * @return Response Une redirection vers la page de la boutique avec le filtre de catégorie
     */
    #[Route('/category/{id}', name: 'app_category')]
    public function categoryFilter(Category $category): Response
    {
        // Redirige vers la route 'app_shop' en ajoutant l'ID de la catégorie comme paramètre
        return $this->redirectToRoute('app_shop', ['category_id' => $category->getId()]);
    }
}
