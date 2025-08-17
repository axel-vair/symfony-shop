<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Product;
use App\Repository\FavoriteRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavoriteController extends AbstractController
{
    #[Route('/favoris', name: 'app_favorite')]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        $favorites = $favoriteRepository->findFavoritesByUser($this->getUser());
        return $this->render('pages/favorite/index.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/favoris/ajout/{id}', name: 'app_favorite_add')]
    public function add(
        Product $product,
        EntityManagerInterface $entityManager,
        FavoriteRepository $favoriteRepository,
    ): Response
    {
        $user = $this->getUser();
        $existingFavorite = $favoriteRepository->findOneBy([
            'userAccount' => $user,
            'product' => $product,
        ]);

        if ($existingFavorite) {
            $this->addFlash('danger', 'Ce produit est déjà dans vos favoris');
            return $this->redirectToRoute('app_product_show', ['slug' => $product->getSlug()]);
        }

        $favorite = new Favorite();
        $favorite->setUserAccount($this->getUser());
        $favorite->setProduct($product);
        $favorite->setCreatedAt(new DateTime());


        $entityManager->persist($favorite);
        $entityManager->flush();

        $this->addFlash('success', 'Le produit a été ajouté à vos favoris');


        return $this->redirectToRoute('app_product_show',
            ['slug' => $product->getSlug()]
        );
    }

    /**
     * Delete from product page
     * @param Product $product
     * @param EntityManagerInterface $entityManager
     * @param FavoriteRepository $favoriteRepository
     * @return Response
     */
    #[Route('favori/suppression/produit/{id}', name: 'app_favorite_delete_by_product')]
    public function deleteByProduct(
        Product $product,
        EntityManagerInterface $entityManager,
        FavoriteRepository $favoriteRepository
    ): Response
    {
        $user = $this->getUser();

        $existingFavorite = $favoriteRepository->findOneBy([
            'userAccount' => $user,
            'product' => $product,
        ]);


        $entityManager->remove($existingFavorite);
        $entityManager->flush();
        $this->addFlash('success', 'Le produit a bien été retiré de vos favoris');

        return $this->redirectToRoute('app_product_show', ['slug' => $product->getSlug()]);

    }

    /**
     * Delete from favorite page
     * @param Favorite $favorite
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('favoris/suppression/{id}', name: 'app_favorite_delete')]
    public function delete(
        Favorite $favorite,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $user = $this->getUser();

        $entityManager->remove($favorite);
        $entityManager->flush();
        $this->addFlash('success', 'Le produit a bien été retiré de vos favoris');

        return $this->redirectToRoute('app_favorite');
    }
}
