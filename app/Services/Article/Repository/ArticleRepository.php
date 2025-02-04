<?php

declare(strict_types=1);

namespace App\Services\Article\Repository;

use App\Entities\Article;
use Doctrine\ORM\EntityRepository;
use Illuminate\Routing\Route;

class ArticleRepository extends EntityRepository
{
    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int|string|Route|null $id): ?Article
    {
        return $this->find($id);
    }
}
