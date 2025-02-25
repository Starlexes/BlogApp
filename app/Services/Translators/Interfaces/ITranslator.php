<?php

declare(strict_types=1);

namespace App\Services\Translators\Interfaces;

interface ITranslator
{
    public function translate(string $text, string $sourceLang, string $targetLang): string;
}
