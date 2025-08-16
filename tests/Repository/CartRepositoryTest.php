<?php

namespace App\Tests\Repository;

use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CartRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager = $entityManager;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->entityManager !== null) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }

    public function testRepositoryIsEmptyInitially(): void
    {
        if ($this->entityManager === null) {
            $this->fail('EntityManager not initialized');
        }

        $carts = $this->entityManager->getRepository(Cart::class)->findAll();

        $this->assertCount(0, $carts);
    }

    public function testCanPersistAndRetrieveCart(): void
    {
        if ($this->entityManager === null) {
            $this->fail('EntityManager not initialized');
        }

        $cart = new Cart();
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        $fetchedCart = $this->entityManager->getRepository(Cart::class)->find($cart->getId());

        $this->assertNotNull($fetchedCart);
        $this->assertSame($cart->getId(), $fetchedCart->getId());
    }
}
