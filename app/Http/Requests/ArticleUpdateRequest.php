<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Entities\Article;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ArticleUpdateRequest extends FormRequest
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
        $articleRepository = $this->em->getRepository(Article::class);
        $articleId = $this->route('id');
        $article = $articleRepository->getById($articleId);

        return $user && $user->getAuthIdentifier() === $article->getUser()->getId();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'userId' => 'required|int',
        ];
    }

    public function getTitle(): string
    {
        return $this->input('title');
    }

    public function getContentText(): string
    {
        return $this->input('content');
    }

    public function getUserId(): int
    {
        return $this->input('userId');
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 400));
    }
}
