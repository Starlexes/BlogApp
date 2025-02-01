<?php

namespace App\Factories;

use App\DTO\User\UserDTO;
use App\Entities\User;
use Illuminate\Support\Facades\Hash;

class UserFactory
{
    public static function create(UserDTO $dto): User
    {
        $user = new User;
        $user->setName($dto->name);
        $user->setEmail($dto->email);
        $user->setPassword(Hash::make($dto->password));

        return $user;
    }

    public static function update(User $user, UserDTO $dto): User
    {
        $user->setName($dto->name);
        $user->setEmail($dto->email);

        return $user;
    }
}
