<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Entities\Article;
use App\Services\Article\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ArticleDeletionRequest extends FormRequest
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::guard('api')->user();

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->em->getRepository(Article::class);

        $articleId = $this->route('id');

        $article = $articleRepository->getById($articleId);

        return $user && $user->getAuthIdentifier() === $article->getUser()->getId();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 400));
    }
}
