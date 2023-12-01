<?php

namespace App\Models;

class User
{
    private ?int $id;
    private string $name;
    private string $lastName;
    private string $email;
    private ?string $password;
    private ?int $roleId;

    public function __construct() { }

    private function hashPassword(string $password): string
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $hashedPassword;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(?string $password): void
    {
        $password ? $this->password = $this->hashPassword($password) : $this->password = null;
    }

    public function setRoleId(?int $roleId): void
    {
        $this->roleId = $roleId;
    }

    public static function hydrate(array $data): User
    {
        $user = new User();
        $user->setId($data['id'] ?? null);
        $user->setName($data['name']);
        $user->setLastName($data['lastName'] ?? $data['last_name']);
        $user->setEmail($data['email']);
        if($data['password'] ?? false)
            $user->setPassword($data['password']);
        else
            $user->setPassword(null);
        $user->setRoleId($data['roleId'] ?? $data['role_id'] ?? null);

        return $user;
    }

    public static function verifyPassword(string $passwordHash, string $password): bool
    {
        return password_verify($password, $passwordHash);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'roleId' => $this->roleId ?? null
        ];
    }
}