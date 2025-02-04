<?php

declare(strict_types=1);

namespace App\Services\User\Repository;

use App\Entities\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $id): ?User
    {
        return $this->find($id);
    }
}
