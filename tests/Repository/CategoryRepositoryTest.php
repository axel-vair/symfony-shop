<?php

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager = $entityManager;

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $this->categoryRepository = $categoryRepository;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
    }

    public function testFindCategoryByName(): void
    {
        $category = new Category();
        $category->setName('Test Category');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $foundCategory = $this->categoryRepository->findOneBy(['name' => 'Test Category']);

        $this->assertNotNull($foundCategory);
        $this->assertSame('Test Category', $foundCategory->getName());
    }

    public function testFindCategoryByNameCaseInsensitive(): void
    {
        $category = new Category();
        $category->setName('CaseInsensitiveTest');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $foundCategory = $this->categoryRepository->createQueryBuilder('c')
            ->where('LOWER(c.name) = LOWER(:name)')
            ->setParameter('name', 'caseinsensitivetest')
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNotNull($foundCategory);
        $this->assertSame('CaseInsensitiveTest', $foundCategory->getName());
    }

    public function testFindNonExistentCategory(): void
    {
        $foundCategory = $this->categoryRepository->findOneBy(['name' => 'NonExistentCategory']);

        $this->assertNull($foundCategory);
    }
}
