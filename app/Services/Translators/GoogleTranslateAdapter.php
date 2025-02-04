<?php

declare(strict_types=1);

namespace App\Services\Translators;

use App\Services\Translators\Interfaces\ITranslator;
use Dejurin\GoogleTranslateForFree;

class GoogleTranslateAdapter implements ITranslator
{
    private static ?self $instance = null;

    private GoogleTranslateForFree $translator;

    private function __construct()
    {
        $this->translator = new GoogleTranslateForFree;
    }

    public static function getInstance(): self
    {
        if (! self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        $result = $this->translator->translate($sourceLang, $targetLang, $text);

        return $result ?: $text;
    }
}
