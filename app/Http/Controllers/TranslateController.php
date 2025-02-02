<?php

namespace App\Http\Controllers;

use App\Services\Translators\Interfaces\ITranslator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslateController extends Controller
{
    private ITranslator $translator;

    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    public function translate(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'text' => 'required|string',
            'source_lang' => 'required|string|size:2',
            'target_lang' => 'required|string|size:2',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $translated = $this->translator->translate(
            $data['text'],
            $data['source_lang'],
            $data['target_lang'],
        );

        return response()->json([
            'translated_text' => $translated,
        ]);
    }
}
