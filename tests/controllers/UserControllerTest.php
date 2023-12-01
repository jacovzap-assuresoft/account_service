<?php

namespace App\Tests\Controllers;

use App\Controllers\UserController;
use App\Models\User;
use App\Models\Mappers\UserMapper;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
    public function testCreateSuccess()
    {
        $userData = [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'secret',
            'role_id' => 1,
        ];

        $user = User::hydrate($userData);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn($user);

        $controller = new UserController($userRepositoryMock);
        $response = $controller->create($userData);

        $expected = [
            'data' => UserMapper::toArrayWithoutPassword($user),
            'status' => 201,
        ];

        $this->assertSame($expected, $response);
    }

    public function testCreateFailure()
    {
        $userData = [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'secret',
            'role_id' => 1,
        ];

        $exceptionMessage = 'Error creating user';
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->once())
            ->method('create')
            ->willThrowException(new \Exception($exceptionMessage));

        $controller = new UserController($userRepositoryMock);
        $response = $controller->create($userData);

        $this->assertSame($exceptionMessage, $response['data']['error']);
        $this->assertArrayHasKey('trace', $response['data']);
        $this->assertIsArray($response['data']['trace']);
        $this->assertNotEmpty($response['data']['trace']);
        $this->assertSame(500, $response['status']);
    }

    public function testDelete()
    {
        $userId = 1;

        $user = new User();
        $user->setId($userId);
        $user->setName('John');
        $user->setLastName('Doe');
        $user->setEmail('test@mail.com');
        $user->setPassword('secret');
        $user->setRoleId(1);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willReturn($user);

        $controller = new UserController($userRepositoryMock);
        $response = $controller->delete($userId);

        $this->assertSame(UserMapper::toArrayWithoutPassword($user), $response['data']);
        $this->assertSame(200, $response['status']);
    }

    public function testDeleteFailure()
    {
        $userId = 1;

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willThrowException(new \Exception('Error deleting user'));

        $controller = new UserController($userRepositoryMock);
        $response = $controller->delete($userId);

        $this->assertArrayHasKey('trace', $response['data']);
        $this->assertIsArray($response['data']['trace']);
        $this->assertNotEmpty($response['data']['trace']);
        $this->assertSame(404, $response['status']);
    }

    public function testGetAllSuccess()
    {
        $usersData = [
            [
                'id' => 1,
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => 'secret',
                'role_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane.doe@example.com',
                'password' => 'topsecret',
                'role_id' => 2,
            ],
        ];

        $users = [];
        foreach ($usersData as $userData) {
            $user = User::hydrate($userData);
            $users[] = $user;
        }

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->once())
            ->method('getAll')
            ->willReturn($users);

        $controller = new UserController($userRepositoryMock);
        $response = $controller->getAll();

        $expected = [
            'data' => [
                [
                    'id' => 1,
                    'name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john.doe@example.com',
                    'role_id' => 1,
                ],
                [
                    'id' => 2,
                    'name' => 'Jane',
                    'last_name' => 'Doe',
                    'email' => 'jane.doe@example.com',
                    'role_id' => 2,
                ],
            ],
            'status' => 200,
        ];

        foreach ($expected['data'] as $key => $user) {
            $this->assertArrayNotHasKey('password', $response['data'][$key]);
        }
        $this->assertEquals($expected['status'], $response['status']);
    }
}
