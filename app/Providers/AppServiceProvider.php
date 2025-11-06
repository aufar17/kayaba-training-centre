<?php

namespace App\Providers;

use App\Interfaces\RepositoryInterface\TrainingRepositoryInterface;
use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use App\Repositories\TrainingRepository;
use App\Services\TrainingService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Service
        $this->app->bind(TrainingServiceInterface::class, TrainingService::class);

        //Repository
        $this->app->bind(TrainingRepositoryInterface::class, TrainingRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
