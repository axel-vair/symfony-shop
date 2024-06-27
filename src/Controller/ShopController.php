<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] ?int $category_id = null
    ): Response {
        $queryBuilder = $productRepository->createQueryBuilder('p');

        if ($category_id) {
            $queryBuilder->andWhere('p.category = :category_id')
                ->setParameter('category_id', $category_id);
        }

        $categories = $categoryRepository->findAll();
        $adapter = new QueryAdapter($queryBuilder);
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $page,
            6
        );

        return $this->render('pages/shop/index.html.twig', [
            'products' => $pager,
            'categories' => $categories,
            'current_category_id' => $category_id,
        ]);
    }
}