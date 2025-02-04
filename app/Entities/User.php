<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

#[ORM\Entity(repositoryClass: "App\Services\User\Repository\UserRepository")]
#[ORM\Table(name: 'users')]
class User implements Authenticatable, JWTSubject
{
    #[ORM\Column(type: 'string')]
    protected string $password;

    #[ORM\Column(name: 'remember_token', type: 'string', nullable: true)]
    private string $rememberToken;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint', options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'user')]
    private Collection $articles;

    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function setArticles(Collection $articles): self
    {
        $this->articles = $articles;

        return $this;
    }

    public function getJWTIdentifier(): int
    {
        return $this->getId();
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

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    public function setRememberToken($value): self
    {
        $this->rememberToken = $value;

        return $this;
    }

    public function getRememberTokenName(): string
    {
        return 'rememberToken';
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): int
    {
        return $this->getId();
    }

    public function getAuthPassword(): string
    {
        return $this->getPassword();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }
}
