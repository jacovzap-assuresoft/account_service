<?php

namespace App\Controllers;

use App\Models\Mappers\UserMapper;
use App\Models\User;
use App\Repositories\UserRepository;
use Flight;

class UserController
{
    private readonly UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($userData)
    {
        try {
            $user = User::hydrate($userData);
            $user = $this->repository->create($user);

            return [
                "data" => UserMapper::toArrayWithoutPassword($user),
                "status" => 201
            ];
        } catch (\Exception $e) {
            return [
                "data" => [
                    "error" => $e->getMessage(),
                    "status" => $e->getCode() ?? 500,
                    "trace" => $e->getTrace()
                ],
                "status" => $e->getCode() ?? 500
            ];
        }
    }

    public function getById(int $id)
    {
        try {
            $user = $this->repository->getById($id);
            return [
                "data" => UserMapper::toArrayWithoutPassword($user),
                "status" => 200
            ];
        } catch (\Exception $e) {
            return [
                "data" => [
                    "error" => $e->getMessage(),
                    "trace" => $e->getTrace()
                ],
                "status" => 404
            ];
        }
    }

    public function getAll()
    {
        try {
            $users = $this->repository->getAll();
            $users = array_map(function ($user) {
                return UserMapper::toArrayWithoutPassword($user);
            }, $users);
            return [
                "data" => $users,
                "status" => 200
            ];
        } catch (\Exception $e) {
            return [
                "data" => [
                    "error" => $e->getMessage(),
                    "trace" => $e->getTrace()
                ],
                "status" => 404
            ];
        }
    }

    public function update(int $id, array $userData)
    {
        try {
            $user = User::hydrate($userData);
            $user->setId($id);
            $user = $this->repository->update($user);
            return [
                "data" => UserMapper::toArrayWithoutPassword($user),
                "status" => 200
            ];
        } catch (\Exception $e) {
            return [
                "data" => [
                    "error" => $e->getMessage(),
                    "trace" => $e->getTrace()
                ],
                "status" => 404
            ];
        }
    }

    public function delete(int $id)
    {
        try {
            $user = $this->repository->delete($id);
            return [
                "data" => UserMapper::toArrayWithoutPassword($user),
                "status" => 200
            ];
        } catch (\Exception $e) {
            return [
                "data" => [
                    "error" => $e->getMessage(),
                    "trace" => $e->getTrace()
                ],
                "status" => 404
            ];
        }
    }
}