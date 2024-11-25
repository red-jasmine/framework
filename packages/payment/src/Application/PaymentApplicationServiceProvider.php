<?php

namespace RedJasmine\Payment\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Domain\Repositories\ChannelAppReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\ChannelAppReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\ChannelProductReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\ChannelReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\MerchantAppReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\MerchantReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\TradeReadRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\ChannelAppRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\ChannelProductRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\ChannelRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantAppRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\TradeRepository;

class PaymentApplicationServiceProvider extends ServiceProvider
{

    public function register() : void
    {

        $this->app->bind(MerchantRepositoryInterface::class, MerchantRepository::class);
        $this->app->bind(MerchantReadRepositoryInterface::class, MerchantReadRepository::class);

        $this->app->bind(MerchantAppRepositoryInterface::class, MerchantAppRepository::class);
        $this->app->bind(MerchantAppReadRepositoryInterface::class, MerchantAppReadRepository::class);

        $this->app->bind(ChannelAppRepositoryInterface::class, ChannelAppRepository::class);
        $this->app->bind(ChannelAppReadRepositoryInterface::class, ChannelAppReadRepository::class);

        $this->app->bind(ChannelRepositoryInterface::class, ChannelRepository::class);
        $this->app->bind(ChannelReadRepositoryInterface::class, ChannelReadRepository::class);

        $this->app->bind(ChannelProductRepositoryInterface::class, ChannelProductRepository::class);
        $this->app->bind(ChannelProductReadRepositoryInterface::class, ChannelProductReadRepository::class);


        $this->app->bind(TradeRepositoryInterface::class, TradeRepository::class);
        $this->app->bind(TradeReadRepositoryInterface::class, TradeReadRepository::class);


    }

}
