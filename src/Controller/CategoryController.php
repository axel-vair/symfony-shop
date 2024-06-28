<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{name}', name: 'app_category_index')]
    public function index(string $name, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $name]);

        if (!$category) {
            // Recherche insensible à la casse si la première recherche échoue
            $category = $categoryRepository->createQueryBuilder('c')
                ->where('LOWER(c.name) = LOWER(:name)')
                ->setParameter('name', $name)
                ->getQuery()
                ->getOneOrNullResult();
        }

        if (!$category) {
            throw $this->createNotFoundException('La catégorie demandée n\'existe pas');
        }

        return $this->render('pages/category/index.html.twig', [
            'category' => $category
        ]);
    }

    #[Route('/category/{id}', name: 'app_category')]
    public function categoryFilter(Category $category): Response
    {
        return $this->redirectToRoute('app_shop', ['category_id' => $category->getId()]);

    }
}
