<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SubCategoryController extends AbstractController
{
    #[Route('/{categorieSlug}/{subCategorySlug}', name: 'app_sub_category_show')]
    public function show(
        #[MapEntity(expr: 'repository.findOneCategoryBySlugInsensitive(categorieSlug)')]
        ?Category $category,
        #[MapEntity(expr: 'repository.findOneSubCategoryBySlugInsensitive(subCategorySlug)')]
        ?SubCategory $subCategory,
        ProductRepository $productRepository,
    ): Response {
        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }
        if (!$subCategory) {
            throw $this->createNotFoundException('Sous-catégorie non trouvée');
        }

        $products = $productRepository->findBySubCategory($subCategory);
        return $this->render('pages/sub_category/index.html.twig', [
            'category' => $category,
            'subCategory' => $subCategory,
            'products' => $products,
        ]);
    }

}
