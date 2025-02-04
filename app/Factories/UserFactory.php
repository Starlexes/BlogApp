<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\User;
use App\Services\User\DTO\UserDTO;
use Illuminate\Support\Facades\Hash;

class UserFactory
{
    public static function create(UserDTO $dto): User
    {
        $user = new User;
        $user->setName($dto->getName())
            ->setEmail($dto->getEmail())
            ->setPassword(Hash::make($dto->getPassword()));

        return $user;
    }
}
