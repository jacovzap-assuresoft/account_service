<?php

namespace App\Models;

class User
{
    private ?int $id;
    private string $name;
    private string $lastName;
    private string $email;
    private string $password;
    private ?int $roleId;

    public function __construct(?int $id = null, ?string $name = null, ?string $lastName = null, ?string $email = null, ?string $password = null, ?int $roleId = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $this->hashPassword($password);
        $this->roleId = $roleId;
    }

    private function hashPassword(string $password): string
    {
        $options = [
            'cost' => 12,
        ];

        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID, $options);
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoleId(): ?int
    {
        return $this->roleId;
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

    public function setPassword(string $password): void
    {
        $this->password = $this->hashPassword($password);
    }

    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
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