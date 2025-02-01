<?php

namespace App\Factories;

use App\DTO\Article\ArticleDTO;
use App\Entities\Article;
use App\Entities\User;

class ArticleFactory
{
    public static function create(ArticleDTO $dto, User $user): Article
    {
        $article = new Article;
        $article->setTitle($dto->getTitle());
        $article->setContent($dto->getContent());
        $article->setUser($user);

        return $article;
    }

    public static function update(Article $article, ArticleDTO $dto, User $user): Article
    {
        $article->setTitle($dto->getTitle());
        $article->setContent($dto->getContent());
        $article->setUser($user);

        return $article;
    }
}
