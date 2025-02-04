<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\Article;
use App\Entities\User;
use App\Factories\ArticleFactory;
use App\Http\Formatter\Article\ArticleFormatter;
use App\Http\Requests\ArticleCreationRequest;
use App\Services\Article\DTO\ArticleDTO;
use App\Services\Article\Repository\ArticleRepository;
use App\Services\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /* @var UserRepository */
    private readonly EntityRepository $userRepository;

    /* @var  ArticleRepository */
    private readonly EntityRepository $articleRepository;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ArticleFormatter $articleFormatter,
    ) {
        $this->articleRepository = $this->em->getRepository(Article::class);
        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function get(): JsonResponse
    {
        $all = $this->articleRepository->findAll();

        return response()->json($all);
    }

    public function create(ArticleCreationRequest $request): JsonResponse
    {
        $dto = new ArticleDTO(
            $request->getTitle(),
            $request->getContentText(),
            $request->getUserId(),
        );

        $user = $this->userRepository->getById($dto->getUserId());
        $article = ArticleFactory::create($dto, $user);

        $this->em->persist($article);
        $this->em->flush();

        return response()->json($this->articleFormatter->format($article), 201);
    }

    public function getById(int $id): JsonResponse
    {
        $article = $this->articleRepository->getById($id);

        if (! $article) {
            return response()->json('User not found', 404);
        }

        return response()->json($this->articleFormatter->format($article));
    }

    public function update(ArticleCreationRequest $request, int $articleId): JsonResponse
    {
        $dto = new ArticleDTO(
            $request->getTitle(),
            $request->getContentText(),
            $request->getUserId(),
        );

        $article = $this->articleRepository->getById($articleId);

        if (! $article) {
            return response()->json('Article not found', 404);
        }

        $user = $this->userRepository->getById($dto->getUserId());

        $article->setTitle($dto->getTitle())
            ->setContent($dto->getContent())
            ->setUser($user);

        $this->em->persist($article);
        $this->em->flush();

        return response()->json($this->articleFormatter->format($article));
    }

    public function destroy(ArticleCreationRequest $request, int $articleId): JsonResponse
    {
        $article = $this->articleRepository->getById($articleId);

        if (! $article) {
            return response()->json('Article not found', 404);
        }

        $this->em->remove($article);
        $this->em->flush();

        return response()->json($articleId, 204);
    }
}
