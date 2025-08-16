<?php

namespace App\Twig;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    public function __construct(private readonly CategoryRepository $categoryRepository)
    {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('categories', [$this, 'getCategories']),
        ];
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categoryRepository->findAllWithSubCategories();
    }
}
