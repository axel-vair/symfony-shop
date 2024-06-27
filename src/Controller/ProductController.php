<?php

namespace App\Controller;

use App\Entity\Product;
<<<<<<< HEAD
use App\Repository\ProductRepository;
=======
>>>>>>> b488fc18474c4cf2fc2ee584c03bbc626403e0fc
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_id', methods: ['GET'])]
<<<<<<< HEAD
    public function index(Product $product, ProductRepository $productRepository): Response
    {
        return $this->render('product/show.html.twig', [
=======
    public function index(Product $product): Response
    {
        return $this->render('pages/product/show.html.twig', [
>>>>>>> b488fc18474c4cf2fc2ee584c03bbc626403e0fc
            "product" => $product,
        ]);
    }
}
