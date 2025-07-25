<?php
namespace App\Tests\Controller;

use App\Controller\ShopController;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;

class ShopControllerTest extends TestCase
{
    public function testIndexReturnsResponseWithPager()
    {
        $productRepo = $this->createStub(ProductRepository::class);
        $categoryRepo = $this->createStub(CategoryRepository::class);
        $pager = $this->createStub(Pagerfanta::class);
        $pager->method('getCurrentPageResults')->willReturn([]);

        // simuler behavior de QueryAdapter+Pagerfanta si besoin

        $controller = new ShopController();
        $response = $controller->index($productRepo, $categoryRepo, 1, null);

        $this->assertInstanceOf(Response::class, $response);
    }
}
