<?php

namespace App\Services\Translators\Decorators;

use Illuminate\Support\Facades\Log;

class LoggingTranslatorDecorator extends TranslatorDecorator
{
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        Log::info('Translation request', [
            'source_lang' => $sourceLang,
            'target_lang' => $targetLang,
            'text_length' => strlen($text),
        ]);

        $result = $this->translator->translate($text, $sourceLang, $targetLang);

        Log::debug('Translation result', [
            'original' => $text,
            'translated' => $result,
        ]);

        return $result;
    }
}
