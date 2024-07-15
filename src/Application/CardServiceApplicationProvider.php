<?php

namespace RedJasmine\Card\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Card\Domain\Repositories\CardRepositoryInterface;
use RedJasmine\Card\Infrastructure\Repositories\Eloquent\CardRepository;

class CardServiceApplicationProvider extends ServiceProvider
{


    public function register() : void
    {
        $this->app->bind(CardRepositoryInterface::class, CardRepository::class);
    }

}
