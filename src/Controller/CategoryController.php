<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{id}', name: 'app_category')]
    public function index(Category $category): Response
    {
        return $this->redirectToRoute('app_shop', ['category_id' => $category->getId()]);

    }
}
