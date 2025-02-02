<?php

namespace App\Http\Controllers;

use App\DTO\User\UserDTO;
use App\Entities\User;
use App\Factories\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function get(): JsonResponse
    {

        return response()->json(User::all());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function getById(int $id): JsonResponse
    {
        $user = $this->em->find(User::class, $id);
        if (! $user) {
            return response()->json('User not found', 404);
        }

        return response()->json($user->getUserData());
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = $this->validateUserData($data);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $userFactory = new UserFactory;
        $dto = new UserDTO;
        $dto->fromArray($data);
        $user = $userFactory::create($dto);

        $this->em->persist($user);
        $this->em->flush();

        return response()->json($user->getUserData(), 201);
    }

    private function validateUserData(array $dto): \Illuminate\Validation\Validator
    {
        return Validator::make($dto, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->all();
        $validator = $this->validateUserData($data);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = $this->em->find(User::class, $id);
        if (! $user) {
            return response()->json('User not found', 404);
        }
        $userFactory = new UserFactory;
        $dto = new UserDTO;
        $dto->fromArray($data);
        $user = $userFactory::update($user, $dto);

        $this->em->persist($user);
        $this->em->flush();

        return response()->json($user->getUserData());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->em->find(User::class, $id);
        if (! $user) {
            return response()->json('User not found', 404);
        }
        $this->em->remove($user);
        $this->em->flush();

        return response()->json($id, 204);
    }
}
