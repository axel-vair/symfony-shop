<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(
        ProductRepository $productRepository,
        #[MapQueryParameter] int $page = 1
    ): Response {
        $queryBuilder = $productRepository->createQueryBuilder('p');

        $adapter = new QueryAdapter($queryBuilder);
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new ArrayAdapter($productRepository->findAll()),
            $page,
            6);

        return $this->render('pages/shop/index.html.twig', [
            'products' => $pager,
        ]);
    }
}