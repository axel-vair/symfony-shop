<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\OAuthRegistrationService;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class OAuthRegistrationServiceTest extends TestCase
{
    public function testPersistCreatesUser(): void
    {
        $email = 'test@example.com';
        $googleId = 'google123';

        // Mock de ResourceOwnerInterface
        $resourceOwner = $this->createMock(ResourceOwnerInterface::class);
        $resourceOwner->method('toArray')->willReturn(['email' => $email]);
        $resourceOwner->method('getId')->willReturn($googleId);

        // Mock du UserRepository
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function (User $user) use ($email, $googleId) {
                return $user->getEmail() === $email
                    && $user->getGoogleId() === $googleId
                    && $user->getAuthMethod() === 'google'
                    && $user->getPassword() === 'OAUTH_USER'; // mot de passe par défaut
            }), true);

        $service = new OAuthRegistrationService($userRepository);

        $user = $service->persist($resourceOwner);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($googleId, $user->getGoogleId());
        $this->assertEquals('google', $user->getAuthMethod());
        $this->assertEquals('OAUTH_USER', $user->getPassword());
    }

    public function testPersistThrowsExceptionIfEmailMissing(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("L'email OAuth est absent.");

        $resourceOwner = $this->createMock(ResourceOwnerInterface::class);
        $resourceOwner->method('toArray')->willReturn([]); // Pas d’email

        $userRepository = $this->createMock(UserRepository::class);

        $service = new OAuthRegistrationService($userRepository);

        $service->persist($resourceOwner);
    }
}
