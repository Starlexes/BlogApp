<?php

declare(strict_types=1);

namespace App\Services\User\Providers;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAuthProvider implements UserProvider
{
    public function __construct(
        protected EntityManagerInterface $em
    ) {}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        $user = $this->retrieveById($identifier);

        if (! $user) {
            return null;
        }

        $rememberToken = $user->getRememberToken();

        return $rememberToken && hash_equals($rememberToken, $token) ? $user : null;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        return $this->em->find(User::class, $identifier);
    }

    /**
     * @param  string  $token
     */
    public function updateRememberToken(Authenticatable $user, $token): void
    {
        $user->setRememberToken($token);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials)) {
            return null;
        }

        $credentials = array_filter($credentials, function ($key) {
            return ! Str::contains($key, 'password');
        }, ARRAY_FILTER_USE_KEY);

        if (isset($credentials['email'])) {
            return $this->em->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
        }

        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $password = $credentials['password'] ?? '';
        $isValid = password_verify($password, $user->getAuthPassword());

        if ($isValid) {
            $this->rehashPasswordIfRequired($user, $password);
        }

        return $isValid;
    }

    public function rehashPasswordIfRequired(User|Authenticatable $user, string|array $credentials, bool $force = false): void
    {

        if (password_needs_rehash($user->getAuthPassword(), PASSWORD_DEFAULT)) {
            if (is_array($credentials)) {
                $user->setPassword(Hash::make(($credentials['password'])));
            }
            $user->setPassword(Hash::make(($credentials)));

            $this->em->persist($user);
            $this->em->flush();
        }

    }
}
