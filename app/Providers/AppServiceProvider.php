<?php

namespace App\Providers;

use App\Interfaces\RepositoryInterface\LocationRepositoryInterface;
use App\Interfaces\RepositoryInterface\OrganizerRepositoryInterface;
use App\Interfaces\RepositoryInterface\TrainingRepositoryInterface;
use App\Interfaces\ServiceInterface\LocationServiceInterface;
use App\Interfaces\ServiceInterface\OrganizerServiceInterface;
use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use App\Repositories\LocationRepository;
use App\Repositories\OrganizerRepository;
use App\Repositories\TrainingRepository;
use App\Services\LocationService;
use App\Services\OrganizerService;
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
        $this->app->bind(LocationServiceInterface::class, LocationService::class);
        $this->app->bind(OrganizerServiceInterface::class, OrganizerService::class);

        //Repository
        $this->app->bind(TrainingRepositoryInterface::class, TrainingRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(OrganizerRepositoryInterface::class, OrganizerRepository::class);
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
