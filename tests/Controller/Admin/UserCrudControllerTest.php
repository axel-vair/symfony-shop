<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\UserCrudController;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserCrudControllerTest extends WebTestCase
{
    public function testAdminPageRequiresRoleAdmin()
    {
        $client = static::createClient();

        $client->request('GET', '/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CUserCrudController');
        $this->assertTrue(
            $client->getResponse()->isRedirect() || $client->getResponse()->getStatusCode() === 403,
            'Page should redirect or forbid access for unauthenticated users'
        );
    }

    public function testAdminPageAccessibleByAdminUser()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $em = $container->get('doctrine')->getManager();

        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('dummy');

        $em->persist($user);
        $em->flush();

        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CUserCrudController');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('UserCrudController', $client->getRequest()->getUri());
        $this->assertSelectorExists('h1', 'User');
    }
    public function testGetEntityFqcnReturnsUserClassName()
    {
        $this->assertSame(User::class, UserCrudController::getEntityFqcn());
    }
}
