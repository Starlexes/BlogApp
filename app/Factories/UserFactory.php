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
        return new User(
            $dto->getEmail(),
            $dto->getEmail(),
            Hash::make($dto->getPassword()),
        );
    }
}
