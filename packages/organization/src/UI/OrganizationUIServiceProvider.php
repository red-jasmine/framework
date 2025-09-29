<?php

namespace RedJasmine\Organization\UI;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Organization\UI\Http\Owner\OrganizationOwnerRoute;

class OrganizationUIServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'organization');

        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::group(['prefix' => 'api/v1'], function () {
            OrganizationOwnerRoute::api();
        });

        Route::group(['prefix' => 'web/v1'], function () {
            OrganizationOwnerRoute::web();
        });
    }
}
