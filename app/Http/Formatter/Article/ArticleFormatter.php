<?php

declare(strict_types=1);

namespace App\Http\Formatter\Article;

use App\Entities\Article;

class ArticleFormatter
{
    public function format(Article $article): array
    {
        return [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'userId' => $article->getUserId(),
        ];
    }
}
