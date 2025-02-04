<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class TranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string',
            'source_lang' => 'required|string|size:2',
            'target_lang' => 'required|string|size:2',
        ];
    }

    public function getText(): string
    {
        return $this->input('text');
    }

    public function getSourceLang(): string
    {
        return $this->input('source_lang');
    }

    public function getTargetLang(): string
    {
        return $this->input('target_lang');
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 400));
    }
}
