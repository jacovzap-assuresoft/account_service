<?php

namespace App\Models\Mappers;

use App\Models\User;

class UserMapper
{
    public static function toUser(array $data) : User
    {
        $user = User::hydrate($data);
        return $user;
    }

    public static function toArray(User $user) : array
    {
        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'roleId' => $user->getRoleId(),
        ];
        return $data;
    }

    public static function toArrayWithoutPassword(User $user) : array
    {
        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'roleId' => $user->getRoleId(),
        ];
        return $data;
    }
}