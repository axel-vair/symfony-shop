<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    /**
     * Display category by insensitive slug
     * MapEntity handle insensitivity
     * @param Category|null $category
     * @return Response
     */
    #[Route('/categorie/{slug}', name: 'app_category_show')]
    public function show(
        #[MapEntity(expr: 'repository.findOneCategoryBySlugInsensitive(slug)')]
        ?Category $category,
    ): Response
    {
        if (!$category) {
            throw $this->createNotFoundException('La catégorie demandée n\'existe pas');
        }

        return $this->render('pages/category/index.html.twig', [
            'category' => $category
        ]);
    }


}
