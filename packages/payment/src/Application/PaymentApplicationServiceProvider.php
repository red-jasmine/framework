<?php

namespace RedJasmine\Payment\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Domain\Repositories\MerchantReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\MerchantReadRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantRepository;

class PaymentApplicationServiceProvider extends ServiceProvider
{

    public function register() : void
    {

        $this->app->bind(MerchantRepositoryInterface::class, MerchantRepository::class);
        $this->app->bind(MerchantReadRepositoryInterface::class, MerchantReadRepository::class);

    }

}
