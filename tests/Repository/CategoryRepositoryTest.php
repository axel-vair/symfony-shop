<?php
namespace App\Tests\Repository;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        self::bootKernel(); // Démarre le noyau Symfony

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->categoryRepository = self::getContainer()->get(CategoryRepository::class);
    }

    protected function tearDown(): void
    {
        // Nettoyage après chaque test
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testFindCategoryByName()
    {
        // Créer une catégorie en base de données
        $category = new Category();
        $category->setName('Test Category');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $foundCategory = $this->categoryRepository->findOneBy(['name' => 'Test Category']);

        $this->assertNotNull($foundCategory);
        $this->assertSame('Test Category', $foundCategory->getName());
    }

    public function testFindCategoryByNameCaseInsensitive()
    {
        // Créer une catégorie avec un nom en minuscule
        $category = new Category();
        $category->setName('CaseInsensitiveTest');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // Rechercher la catégorie en insensible à la casse
        $foundCategory = $this->categoryRepository->createQueryBuilder('c')
            ->where('LOWER(c.name) = LOWER(:name)')
            ->setParameter('name', 'caseinsensitivetest')
            ->getQuery()
            ->getOneOrNullResult();

        // Vérifier que la catégorie existe même avec une recherche insensible à la casse
        $this->assertNotNull($foundCategory);
        $this->assertSame('CaseInsensitiveTest', $foundCategory->getName());
    }

    public function testFindNonExistentCategory()
    {
        // Rechercher une catégorie inexistante
        $foundCategory = $this->categoryRepository->findOneBy(['name' => 'NonExistentCategory']);

        // Vérifier que la catégorie n'existe pas
        $this->assertNull($foundCategory);
    }
}
