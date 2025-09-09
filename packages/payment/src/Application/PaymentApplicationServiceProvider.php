<?php

namespace RedJasmine\Payment\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\Application\Listeners\AsyncNotifyListener;
use RedJasmine\Payment\Application\Listeners\PaymentChannelListener;
use RedJasmine\Payment\Domain\Events\Notifies\NotifyCreateEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundProcessingEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundSuccessEvent;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferExecutingEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferProcessingEvent;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\NotifyRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\SettleRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Payment\Infrastructure\Repositories\ChannelAppRepository;
use RedJasmine\Payment\Infrastructure\Repositories\ChannelProductRepository;
use RedJasmine\Payment\Infrastructure\Repositories\ChannelRepository;
use RedJasmine\Payment\Infrastructure\Repositories\MerchantAppRepository;
use RedJasmine\Payment\Infrastructure\Repositories\MerchantChannelAppPermissionRepository;
use RedJasmine\Payment\Infrastructure\Repositories\MerchantRepository;
use RedJasmine\Payment\Infrastructure\Repositories\MethodRepository;
use RedJasmine\Payment\Infrastructure\Repositories\NotifyRepository;
use RedJasmine\Payment\Infrastructure\Repositories\RefundRepository;
use RedJasmine\Payment\Infrastructure\Repositories\SettleReceiverRepository;
use RedJasmine\Payment\Infrastructure\Repositories\SettleRepository;
use RedJasmine\Payment\Infrastructure\Repositories\TradeRepository;
use RedJasmine\Payment\Infrastructure\Repositories\TransferRepository;

/**
 * 支付应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class PaymentApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(TradeRepositoryInterface::class, TradeRepository::class);
        $this->app->bind(RefundRepositoryInterface::class, RefundRepository::class);
        $this->app->bind(TransferRepositoryInterface::class, TransferRepository::class);
        $this->app->bind(NotifyRepositoryInterface::class, NotifyRepository::class);
        $this->app->bind(SettleRepositoryInterface::class, SettleRepository::class);
        $this->app->bind(SettleReceiverRepositoryInterface::class, SettleReceiverRepository::class);

        // 商户相关
        $this->app->bind(MerchantRepositoryInterface::class, MerchantRepository::class);
        $this->app->bind(MerchantAppRepositoryInterface::class, MerchantAppRepository::class);
        $this->app->bind(MerchantChannelAppPermissionRepositoryInterface::class, MerchantChannelAppPermissionRepository::class);

        // 渠道相关
        $this->app->bind(ChannelRepositoryInterface::class, ChannelRepository::class);
        $this->app->bind(ChannelAppRepositoryInterface::class, ChannelAppRepository::class);
        $this->app->bind(ChannelProductRepositoryInterface::class, ChannelProductRepository::class);
        $this->app->bind(MethodRepositoryInterface::class, MethodRepository::class);
    }

    public function boot(): void
    {
        // 事件监听
        Event::listen(TradePaidEvent::class, PaymentChannelListener::class);
        Event::listen(RefundCreatedEvent::class, PaymentChannelListener::class);
        Event::listen(RefundProcessingEvent::class, PaymentChannelListener::class);
        Event::listen(RefundSuccessEvent::class, PaymentChannelListener::class);
        Event::listen(TransferExecutingEvent::class, PaymentChannelListener::class);
        Event::listen(TransferProcessingEvent::class, PaymentChannelListener::class);
        Event::listen(NotifyCreateEvent::class, AsyncNotifyListener::class);
    }
}
