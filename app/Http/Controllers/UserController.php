<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\User;
use App\Factories\UserFactory;
use App\Http\Formatter\User\UserFormatter;
use App\Http\Requests\UserCreationRequest;
use App\Services\User\DTO\UserDTO;
use App\Services\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* @var UserRepository */
    private readonly EntityRepository $userRepository;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserFormatter $userFormatter,
    ) {
        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function get(): JsonResponse
    {
        $users = $this->userRepository->getAll();
        $formattedUsers = [];

        foreach ($users as $user) {
            $formattedUsers[] = $this->userFormatter->format($user);
        }

        return response()->json($formattedUsers);
    }

    public function register(UserCreationRequest $request): JsonResponse
    {
        $dto = new UserDTO(
            $request->getName(),
            $request->getEmail(),
            $request->getPassword(),
        );

        $user = UserFactory::create($dto);

        $this->em->persist($user);
        $this->em->flush();

        return response()->json($this->userFormatter->format($user), 201);
    }

    public function update(UserCreationRequest $request, int $id): JsonResponse
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            return response()->json('User not found', 404);
        }

        $dto = new UserDTO(
            $request->getName(),
            $request->getEmail(),
            $request->getPassword(),
        );

        $user->setName($dto->getName())
            ->setEmail($dto->getEmail())
            ->setPassword(Hash::make($dto->getPassword()));

        $this->em->persist($user);
        $this->em->flush();

        return response()->json($this->userFormatter->format($user));
    }

    public function getById(int $id): JsonResponse
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            return response()->json('User not found', 404);
        }

        return response()->json($this->userFormatter->format($user));
    }

    public function destroy(int $id): JsonResponse
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            return response()->json('User not found', 404);
        }

        $this->em->remove($user);
        $this->em->flush();

        return response()->json(['id' => $id], 204);
    }
}
