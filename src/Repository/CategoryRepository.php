<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Method to handle insentitive in slug
     * @param string $slug
     * @return Category|null
     */
    public function findOneCategoryBySlugInsensitive(string $slug): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('LOWER(c.slug) = :slug')
            ->setParameter('slug', strtolower($slug))
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Method that display each category and their subcategory
     * @return array
     */
    public function findAllWithSubCategories(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.subCategories', 's')
            ->addSelect('s')
            ->getQuery()
            ->getResult();
    }
}
