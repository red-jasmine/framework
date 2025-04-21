<?php

namespace RedJasmine\Address\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Address\Domain\Repositories\AddressReadRepositoryInterface;
use RedJasmine\Address\Domain\Repositories\AddressRepositoryInterface;
use RedJasmine\Address\Infrastructure\Infrastructure\ReadRepositories\Mysql\AddressReadRepository;
use RedJasmine\Address\Infrastructure\Repositories\Eloquent\AddressRepository;

class AddressApplicationServiceProvider extends ServiceProvider
{

    public function register() : void
    {

        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);

        $this->app->bind(AddressReadRepositoryInterface::class, AddressReadRepository::class);

    }

}