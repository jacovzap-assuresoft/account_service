<?php

namespace App\Repositories;

use App\Config\DBConnection;
use App\Models\User;
use PDO;

class UserPGRepository implements UserRepository
{
    private PDO $db;

    public function __construct() {
        $this->db = DBConnection::getConnection();
    }

    public function create(User $user): array {
        $query = "INSERT INTO public.user (name, last_name, email, password, role_id) VALUES (:name, :last_name, :email, :password, :role_id)";

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
        return $this->getById((int) $this->db->lastInsertId());
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM public.user WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new \Exception($stmt->errorInfo()[2]);
        }

        $user = $stmt->fetch( \PDO::FETCH_ASSOC );

        if (!$user) {
            throw new \Exception("User not found");
        }

        return $user;
    }
}