<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testDefault()
    {
        $user = new User('email@email.com', 'password');
            $this->assertEquals('email@email.com', $user->getEmail());
            $this->assertEquals('password', $user->getPassword());
    }

}
