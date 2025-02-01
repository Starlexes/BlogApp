<?php

namespace App\DTO\User;

class UserDTO
{
    public string $name;

    public string $email;

    public string $password;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public function fromArray(array $data): void
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}
