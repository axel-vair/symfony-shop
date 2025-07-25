<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageLoads()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form'); // supposons que le formulaire est présent
    }

    public function testOauthConnectInvalidService()
    {
        $client = static::createClient();
        $client->request('GET', '/oauth/connect/invalid');

        $this->assertResponseStatusCodeSame(403); // AccessDeniedException -> 403 Forbidden
    }

    // Vous pouvez mocker ClientRegistry pour tester redirection OAuth
}
