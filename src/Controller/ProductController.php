<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    /**
     * Display product by insensitive slug
     * MapEntity handle insensitivity
     * @param Product $product
     * @return Response
     */
    #[Route('/produit/{slug}', name: 'app_product_show')]
    public function show(
        #[MapEntity(expr: 'repository.findOneProductBySlugInsensitive(slug)')]
        Product $product
    ): Response
    {
        return $this->render('pages/product/show.html.twig', [
            "product" => $product,
        ]);
    }
}
