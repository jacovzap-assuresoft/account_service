<?php

namespace App\Test\Models;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $user = new User();

        $user->setId(1);
        $this->assertEquals(1, $user->getId());

        $user->setName('John');
        $this->assertEquals('John', $user->getName());

        $user->setLastName('Doe');
        $this->assertEquals('Doe', $user->getLastName());

        $user->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $user->getEmail());

        $user->setPassword('password123');
        $this->assertTrue(password_verify('password123', $user->getPassword()));

        $user->setRoleId(2);
        $this->assertEquals(2, $user->getRoleId());
    }

    public function testHydration() {
        $userData = [
            'id' => 1,
            'name' => 'John',
            'lastName' => "Doe",
            'email' => 'email@gmail.com',
            'password' => "password123",
            'roleId' => 2,
        ];

        $user = User::hydrate($userData);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('John', $user->getName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals('email@gmail.com', $user->getEmail());
        $this->assertTrue(password_verify('password123', $user->getPassword()));
        $this->assertEquals(2, $user->getRoleId());
    }

    public function testHydrationWithoutPassword() {
        $userData = [
            'id' => 1,
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'email@gmail.com',
            'roleId' => 2
        ];

        $user = User::hydrate($userData);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('John', $user->getName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals('email@gmail.com', $user->getEmail());
        $this->assertNull($user->getPassword());
        $this->assertEquals(2, $user->getRoleId());
    }

    public function testToArray()
    {
        $user = new User();
        $user->setId(1);
        $user->setName('John');
        $user->setLastName('Doe');
        $user->setEmail('john.doe@example.com');
        $user->setPassword('password123');
        $user->setRoleId(2);

        $expectedArray = [
            'id' => 1,
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => $user->getPassword(),
            'roleId' => 2,
        ];

        $this->assertEquals($expectedArray, $user->toArray());
    }

    public function testPasswordVerification()
    {
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
        $this->assertTrue(User::verifyPassword($passwordHash, 'password123'));
        $this->assertFalse(User::verifyPassword($passwordHash, 'wrongpassword'));
    }
}
