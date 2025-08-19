<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    /**
     * Display product by insensitive slug
     * MapEntity handle insensitivity
     * @param Product|null $product
     * @param FavoriteRepository $favoriteRepository
     * @return Response
     */
    #[Route('/produit/{slug}', name: 'app_product_show')]
    public function show(
        #[MapEntity(expr: 'repository.findOneProductBySlugInsensitive(slug)')]
        ?Product $product,
        FavoriteRepository $favoriteRepository
    ): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $isFavorite = false;


        if ($product === null) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        if($user){
            $favorites = $favoriteRepository->findFavoritesByUser($user);

            foreach ($favorites as $favorite) {
                $favProduct = $favorite->getProduct();
                if ($favProduct !== null && $favProduct->getId() === $product->getId()) {
                    $isFavorite = true;
                    break;
                }
            }
        }

        return $this->render('pages/product/show.html.twig', [
            "product" => $product,
            "isFavorite" => $isFavorite,
        ]);
    }
}
