<?php

namespace App\Http\Controllers;

use App\DTO\Article\ArticleDTO;
use App\Entities\Article;
use App\Entities\User;
use App\Factories\ArticleFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function get(): JsonResponse
    {
        return response()->json(Article::all());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function getById(int $id): JsonResponse
    {
        $article = $this->em->find(Article::class, $id);
        if (! $article) {
            return response()->json('User not found', 404);
        }

        return response()->json($article->getArticleData());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = $this->validateArticleData($data);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $articleFactory = new ArticleFactory;
        $dto = new ArticleDTO;
        $dto->fromArray($data);
        $userId = $dto->getUserId();
        $user = $this->em->find(User::class, $userId);

        $article = $articleFactory::create($dto, $user);

        $this->em->persist($article);
        $this->em->flush();

        return response()->json($article->getArticleData(), 201);
    }

    private function validateArticleData(array $dto): \Illuminate\Validation\Validator
    {
        return Validator::make($dto, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'userId' => 'required|int',
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(Request $request, int $articleId): JsonResponse
    {
        $data = $request->all();
        $validator = $this->validateArticleData($data);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $dto = new ArticleDTO;
        $dto->fromArray($data);
        $article = $this->em->find(Article::class, $articleId);

        if (! $article) {
            return response()->json('Article not found', 404);
        }
        $userId = $dto->getUserId();
        $user = $this->em->find(User::class, $userId);

        if (! $this->checkArticleAuthor($article)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $articleFactory = new ArticleFactory;
        $article = $articleFactory::update($article, $dto, $user);

        $this->em->persist($article);
        $this->em->flush();

        return response()->json($article->getArticleData());
    }

    private function checkArticleAuthor(Article $article): bool
    {
        $user = auth()->user();

        return $user && $user->getAuthIdentifier() === $article->getUser()->getId();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function destroy(int $articleId): JsonResponse
    {
        $article = $this->em->find(Article::class, $articleId);
        if (! $article) {
            return response()->json('Article not found', 404);
        }

        if (! $this->checkArticleAuthor($article)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $this->em->remove($article);
        $this->em->flush();

        return response()->json($articleId, 204);
    }
}
