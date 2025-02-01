<?php

namespace App\DTO\Article;

class ArticleDTO
{
    private string $title;

    private string $content;

    private int $userId;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'userId' => $this->userId,
        ];
    }

    public function fromArray(array $data): void
    {
        $this->setTitle($data['title']);
        $this->setContent($data['content']);
        $this->setUserId($data['userId']);
    }
}
