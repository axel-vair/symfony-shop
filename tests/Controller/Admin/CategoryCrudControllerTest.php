<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\CategoryCrudController;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryCrudControllerTest extends WebTestCase
{
    public function testAdminPageRequiresRoleAdmin(): void
    {
        $client = static::createClient();

        // Accès sans authentification -> redirection / 403
        $client->request('GET', '/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CCategoryCrudController');
        $this->assertTrue(
            $client->getResponse()->isRedirect() || $client->getResponse()->getStatusCode() === 403,
            'Page should redirect or forbid access for unauthenticated users'
        );
    }

    public function testAdminPageAccessibleByAdminUser(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        /** @var ManagerRegistry $doctrine */
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('dummy');

        $em->persist($user);
        $em->flush();

        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CCategoryCrudController');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('CategoryCrudController', $client->getRequest()->getUri());
        $this->assertSelectorExists('h1', 'Catégories');
    }

    /**
     * Test unitaire
     * @return void
     */
    public function testGetEntityFqcnReturnsCategoryClassName(): void
    {
        $this->assertSame(Category::class, CategoryCrudController::getEntityFqcn());
    }
}
