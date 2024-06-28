<?php

namespace App\Controller;

use App\Entity\Product;

use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_id', methods: ['GET'])]
    public function index(Product $product): Response
    {
        return $this->render('pages/product/show.html.twig', [
            "product" => $product,
        ]);
    }
}