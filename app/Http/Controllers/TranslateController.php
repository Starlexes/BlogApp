<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TranslationRequest;
use App\Services\Translators\Interfaces\ITranslator;
use Illuminate\Http\JsonResponse;

class TranslateController extends Controller
{
    private ITranslator $translator;

    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    public function translate(TranslationRequest $request): JsonResponse
    {
        $translated = $this->translator->translate(
            $request->getText(),
            $request->getSourceLang(),
            $request->getTargetLang(),
        );

        return response()->json([
            'translated_text' => $translated,
        ]);
    }
}
