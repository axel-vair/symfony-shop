<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_id', methods: ['GET'])]
    public function index(Product $product): Response
    {
        $category = $product->getCategory();

        return $this->render('pages/product/show.html.twig', [
            "product" => $product,
            "category" => $category

        ]);
    }
}
