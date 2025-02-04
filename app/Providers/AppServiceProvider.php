<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Translators\Decorators\LoggingTranslatorDecorator;
use App\Services\Translators\GoogleTranslateAdapter;
use App\Services\Translators\Interfaces\ITranslator;
use App\Services\User\Providers\UserAuthProvider;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Auth;
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
        Auth::provider('doctrine', function ($app, array $config) {
            return new UserAuthProvider($app->make(EntityManagerInterface::class));
        });
    }
}
