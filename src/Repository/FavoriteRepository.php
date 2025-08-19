<?php

namespace App\Repository;

use App\Entity\Favorite;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favorite>
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    /**
     * @param User $user
     * @return Favorite[] Returns an array of Favorite objects
     */
    public function findFavoritesByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.userAccount = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
