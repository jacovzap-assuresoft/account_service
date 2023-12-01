<?php

namespace Tests\Models\Mappers;

use App\Models\User;
use App\Models\Mappers\UserMapper;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    public function testToUser()
    {
        $data = [
            'id' => 1,
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'hashed_password',
            'roleId' => 2,
        ];

        $user = UserMapper::toUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['id'], $user->getId());
        $this->assertEquals($data['name'], $user->getName());
        $this->assertEquals($data['lastName'], $user->getLastName());
        $this->assertEquals($data['email'], $user->getEmail());
        $this->assertTrue(password_verify($data['password'], $user->getPassword()));
        $this->assertEquals($data['roleId'], $user->getRoleId());
    }

    public function testToArray()
    {
        $user = new User();
        $user->setId(1);
        $user->setName('John');
        $user->setLastName('Doe');
        $user->setEmail('john.doe@example.com');
        $user->setPassword('hashed_password');
        $user->setRoleId(2);

        $data = UserMapper::toArray($user);

        $this->assertEquals($user->getId(), $data['id']);
        $this->assertEquals($user->getName(), $data['name']);
        $this->assertEquals($user->getLastName(), $data['lastName']);
        $this->assertEquals($user->getEmail(), $data['email']);
        $this->assertEquals($user->getPassword(), $data['password']);
        $this->assertEquals($user->getRoleId(), $data['roleId']);
    }

    public function testToArrayWithoutPassword()
    {
        $user = new User();
        $user->setId(1);
        $user->setName('John');
        $user->setLastName('Doe');
        $user->setEmail('john.doe@example.com');
        $user->setRoleId(2);

        $data = UserMapper::toArrayWithoutPassword($user);

        $this->assertEquals($user->getId(), $data['id']);
        $this->assertEquals($user->getName(), $data['name']);
        $this->assertEquals($user->getLastName(), $data['lastName']);
        $this->assertEquals($user->getEmail(), $data['email']);
        $this->assertArrayNotHasKey('password', $data);
        $this->assertEquals($user->getRoleId(), $data['roleId']);
    }
}
