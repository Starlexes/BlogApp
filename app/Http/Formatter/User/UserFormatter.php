<?php

declare(strict_types=1);

namespace App\Http\Formatter\User;

use App\Entities\User;

class UserFormatter
{
    public function format(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ];
    }
}
