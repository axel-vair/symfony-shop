<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\SubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Method to handle insentitive in slug
     * @param string $slug
     * @return Product|null
     */
    public function findOneProductBySlugInsensitive(string $slug): ?Product
    {
        return $this->createQueryBuilder('p')
            ->where('LOWER(p.slug) = :slug')
            ->setParameter('slug', strtolower($slug))
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Method filter by product by subcategory
     * @param SubCategory $subCategory
     * @return Product[]
     */
    public function findBySubCategory(SubCategory $subCategory): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.subCategory = :subCategory')
            ->setParameter('subCategory', $subCategory)
            ->getQuery()
            ->getResult();
    }
}
