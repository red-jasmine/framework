<?php

namespace RedJasmine\Interaction\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordReadRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticReadRepositoryInterface;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;
use RedJasmine\Interaction\Infrastructure\ReadRepositories\Mysql\InteractionRecordReadRepository;
use RedJasmine\Interaction\Infrastructure\ReadRepositories\Mysql\InteractionStatisticReadRepository;
use RedJasmine\Interaction\Infrastructure\Repositories\Eloquent\InteractionRecordRepository;
use RedJasmine\Interaction\Infrastructure\Repositories\Eloquent\InteractionStatisticRepository;

class InteractionApplicationServiceProvider extends ServiceProvider
{

    public function register() : void
    {

        $this->app->bind(InteractionStatisticRepositoryInterface::class, InteractionStatisticRepository::class);
        $this->app->bind(InteractionStatisticReadRepositoryInterface::class, InteractionStatisticReadRepository::class);


        $this->app->bind(InteractionRecordRepositoryInterface::class, InteractionRecordRepository::class);
        $this->app->bind(InteractionRecordReadRepositoryInterface::class, InteractionRecordReadRepository::class);


    }


}