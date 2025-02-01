<?php

namespace App\Services\Translators\Decorators;

use App\Services\Translators\Interfaces\ITranslator;

abstract class TranslatorDecorator implements ITranslator
{
    protected ITranslator $translator;

    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }
}
