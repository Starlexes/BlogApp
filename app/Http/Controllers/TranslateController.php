<?php

// app/Http/Controllers/TranslateController.php

namespace App\Http\Controllers;

use App\Services\Translators\Interfaces\ITranslator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
    private ITranslator $translator;

    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    public function translate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'source_lang' => 'required|string|size:2',
            'target_lang' => 'required|string|size:2',
        ]);

        $translated = $this->translator->translate(
            $validated['text'],
            $validated['source_lang'] ?? null,
            $validated['target_lang'] ?? null,
        );

        return response()->json([
            'translated_text' => $translated,
        ]);
    }
}
