<?php

declare(strict_types=1);

namespace App\Entities;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "articles")]
#[ORM\Entity(repositoryClass: "App\Services\Article\Repository\ArticleRepository")]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint", options: ["unsigned" => true])]
    private int $id;

    #[ORM\Column(type: "string")]
    private string $title;

    #[ORM\Column(type: "text")]
    private string $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "articles")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private User $user;

    public function __construct(
        string $title,
        string $content,
        User $user
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->user = $user;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user->getId();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
