<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\Article;
use App\Entities\User;
use App\Services\Article\DTO\ArticleDTO;

class ArticleFactory
{
    public static function create(ArticleDTO $dto, User $user): Article
    {
        return new Article(
            $dto->getTitle(),
            $dto->getContent(),
            $user
        );
    }
}
