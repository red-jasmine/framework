<?php

namespace RedJasmine\Region\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Region\Domain\Repositories\CountryReadRepositoryInterface;
use RedJasmine\Region\Domain\Repositories\CountryRepositoryInterface;
use RedJasmine\Region\Domain\Repositories\RegionReadRepositoryInterface;
use RedJasmine\Region\Domain\Repositories\RegionRepositoryInterface;
use RedJasmine\Region\Infrastructure\ReadRepositories\Mysql\CountryReadRepository;
use RedJasmine\Region\Infrastructure\ReadRepositories\Mysql\RegionReadRepository;
use RedJasmine\Region\Infrastructure\Repositories\Eloquent\CountryRepository;
use RedJasmine\Region\Infrastructure\Repositories\Eloquent\RegionRepository;

class RegionApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(RegionReadRepositoryInterface::class, RegionReadRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);


        $this->app->bind(CountryReadRepositoryInterface::class, CountryReadRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);

    }
}