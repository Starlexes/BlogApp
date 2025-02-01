<?php

namespace App\Providers;

use App\Services\Translators\Decorators\LoggingTranslatorDecorator;
use App\Services\Translators\GoogleTranslateAdapter;
use App\Services\Translators\Interfaces\ITranslator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ITranslator::class, function () {
            $baseTranslator = GoogleTranslateAdapter::getInstance();

            return new LoggingTranslatorDecorator($baseTranslator);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
