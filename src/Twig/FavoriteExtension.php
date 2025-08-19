<?php

namespace App\Twig;

use App\Entity\Favorite;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FavoriteExtension extends AbstractExtension
{
    public function __construct(private readonly FavoriteRepository $favoriteRepository)
    {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('favorite_count', [$this, 'getFavoriteCount']),
        ];
    }

    /**
     * @param $user
     * @return int
     */
    public function getFavoriteCount(User $user): int
    {
        $favorites = $this->favoriteRepository->findFavoritesByUser($user);

        return count($favorites);
    }
}
