<?php

namespace RedJasmine\Logistics\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyReadRepositoryInterface;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyRepositoryInterface;
use RedJasmine\Logistics\Domain\Repositories\LogisticsFreightTemplateReadRepositoryInterface;
use RedJasmine\Logistics\Domain\Repositories\LogisticsFreightTemplateRepositoryInterface;
use RedJasmine\Logistics\Infrastructure\ReadRepositories\Mysql\LogisticsCompanyReadRepository;
use RedJasmine\Logistics\Infrastructure\ReadRepositories\Mysql\LogisticsFreightTemplateReadRepository;
use RedJasmine\Logistics\Infrastructure\Repositories\LogisticsCompanyRepository;
use RedJasmine\Logistics\Infrastructure\Repositories\LogisticsFreightTemplateRepository;

class LogisticsApplicationServiceProvider extends ServiceProvider
{


    public function register() : void
    {

        $this->app->bind(LogisticsFreightTemplateReadRepositoryInterface::class, LogisticsFreightTemplateReadRepository::class);
        $this->app->bind(LogisticsFreightTemplateRepositoryInterface::class, LogisticsFreightTemplateRepository::class);


        $this->app->bind(LogisticsCompanyReadRepositoryInterface::class, LogisticsCompanyReadRepository::class);
        $this->app->bind(LogisticsCompanyRepositoryInterface::class, LogisticsCompanyRepository::class);
    }

}