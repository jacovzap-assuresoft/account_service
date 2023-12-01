<?php

namespace App\Repositories;

use App\Config\DBConnection;
use App\Models\Mappers\UserMapper;
use App\Models\User;
use PDO;

class UserPGRepository implements UserRepository
{
    private PDO $db;
    public function __construct()
    {
        $this->db = DBConnection::getConnection();
    }

    public function create(User $user) : User
    {
        $query = "INSERT INTO public.user (name, last_name, email, password, role_id) VALUES (:name, :last_name, :email, :password, :role_id) RETURNING *";

        $stmt = $this->db->prepare($query);
        print_r($user);
        $stmt->bindValue(':name', $user->getName());
        $stmt->bindValue(':last_name', $user->getLastName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':role_id', $user->getRoleId());

        if (!$stmt->execute()) {
            throw new \Exception($stmt->errorInfo()[2]);
        }
        return UserMapper::toUser($stmt->fetch(\PDO::FETCH_ASSOC));
    }

    public function getById(int $id) : User
    {
        $query = "SELECT * FROM public.user WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new \Exception($stmt->errorInfo()[2]);
        }

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            throw new \Exception("User not found");
        }

        return UserMapper::toUser($user);
    }

    public function getAll() : array
    {
        $query = "SELECT * FROM public.user";

        $stmt = $this->db->prepare($query);

        if (!$stmt->execute()) {
            throw new \Exception($stmt->errorInfo()[2]);
        }

        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$users) {
            throw new \Exception("Users not found");
        }

        return array_map(function ($user) {
            return UserMapper::toUser($user);
        }, $users);
    }

    public function update(User $user) : User
    {
        $query = "UPDATE public.user SET name = :name, last_name = :last_name, email = :email, password = :password, role_id = :role_id WHERE id = :id RETURNING *";

        $userFromDB = $this->getById($user->getId());

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $user->getId());
        $stmt->bindValue(':name', $user->getName() ?? $userFromDB->getName());
        $stmt->bindValue(':last_name', $user->getLastName() ?? $userFromDB->getLastName());
        $stmt->bindValue(':email', $user->getEmail() ?? $userFromDB->getEmail());
        $stmt->bindValue(':password', $user->getPassword() ?? $userFromDB->getPassword());
        $stmt->bindValue(':role_id', $user->getRoleId() ?? $userFromDB->getRoleId());

        if (!$stmt->execute()) {
            throw new \Exception($stmt->errorInfo()[2]);
        }

        return UserMapper::toUser($stmt->fetch(\PDO::FETCH_ASSOC));
    }

    public function delete(int $id) : User
    {
        $query = "DELETE FROM public.user WHERE id = :id RETURNING *";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new \Exception($stmt->errorInfo()[2]);
        }

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            throw new \Exception("User not found");
        }

        return UserMapper::toUser($user);
    }
}