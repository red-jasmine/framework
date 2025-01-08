<?php

namespace RedJasmine\Payment\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Application\Listeners\AsyncNotifyListener;
use RedJasmine\Payment\Application\Listeners\NotifyListener;
use RedJasmine\Payment\Application\Listeners\RefundChannelListener;
use RedJasmine\Payment\Domain\Events\Notifies\NotifyCreateEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundProcessingEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundSuccessEvent;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Payment\Domain\Repositories\ChannelAppReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MethodReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\NotifyReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\NotifyRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\RefundReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\ChannelAppReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\ChannelProductReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\ChannelReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\MerchantAppPermissionReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\MerchantChannelAppReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\MerchantReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\MethodReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\NotifyReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\RefundReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\TradeReadRepository;
use RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql\TransferReadRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\ChannelAppRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\ChannelProductRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\ChannelRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantAppRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantChannelAppPermissionRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MethodRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\NotifyRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\RefundRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\TradeRepository;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\TransferRepository;

class PaymentApplicationServiceProvider extends ServiceProvider
{

    public function register() : void
    {

        $this->app->bind(MerchantRepositoryInterface::class, MerchantRepository::class);
        $this->app->bind(MerchantReadRepositoryInterface::class, MerchantReadRepository::class);

        $this->app->bind(MerchantAppRepositoryInterface::class, MerchantAppRepository::class);
        $this->app->bind(MerchantAppReadRepositoryInterface::class, MerchantAppPermissionReadRepository::class);

        $this->app->bind(ChannelAppRepositoryInterface::class, ChannelAppRepository::class);
        $this->app->bind(ChannelAppReadRepositoryInterface::class, ChannelAppReadRepository::class);

        $this->app->bind(ChannelRepositoryInterface::class, ChannelRepository::class);
        $this->app->bind(ChannelReadRepositoryInterface::class, ChannelReadRepository::class);

        $this->app->bind(ChannelProductRepositoryInterface::class, ChannelProductRepository::class);
        $this->app->bind(ChannelProductReadRepositoryInterface::class, ChannelProductReadRepository::class);


        $this->app->bind(MethodRepositoryInterface::class, MethodRepository::class);
        $this->app->bind(MethodReadRepositoryInterface::class, MethodReadRepository::class);

        $this->app->bind(
            MerchantChannelAppPermissionRepositoryInterface::class,
            MerchantChannelAppPermissionRepository::class
        );
        $this->app->bind(
            MerchantChannelAppPermissionReadRepositoryInterface::class,
            MerchantChannelAppReadRepository::class
        );


        $this->app->bind(TransferRepositoryInterface::class, TransferRepository::class);
        $this->app->bind(TransferReadRepositoryInterface::class, TransferReadRepository::class);

        $this->app->bind(TradeRepositoryInterface::class, TradeRepository::class);
        $this->app->bind(TradeReadRepositoryInterface::class, TradeReadRepository::class);

        $this->app->bind(RefundRepositoryInterface::class, RefundRepository::class);
        $this->app->bind(RefundReadRepositoryInterface::class, RefundReadRepository::class);

        $this->app->bind(NotifyRepositoryInterface::class, NotifyRepository::class);
        $this->app->bind(NotifyReadRepositoryInterface::class, NotifyReadRepository::class);


    }

    public function boot() : void
    {

        Event::listen(RefundCreatedEvent::class, RefundChannelListener::class);
        Event::listen(RefundProcessingEvent::class, RefundChannelListener::class);

        // 注册通知 通知监听器
        Event::listen(TradePaidEvent::class, AsyncNotifyListener::class);
        Event::listen(RefundSuccessEvent::class, AsyncNotifyListener::class);


        Event::listen(NotifyCreateEvent::class, NotifyListener::class);

    }

}
