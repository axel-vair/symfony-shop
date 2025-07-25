<?php
namespace App\Tests\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
{
    public function testEditAccessDeniedIfNotOwner()
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepo->find(1);
        $otherUser = $userRepo->find(2);

        $client->loginUser($otherUser);
        $client->request('GET', '/utilisateur/edition/' . $user->getId());
        $this->assertResponseStatusCodeSame(403);
    }

    public function testEditFormDisplayed()
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepo->find(1);

        $client->loginUser($user);
        $crawler = $client->request('GET', '/utilisateur/edition/' . $user->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="user"]');
    }
}
