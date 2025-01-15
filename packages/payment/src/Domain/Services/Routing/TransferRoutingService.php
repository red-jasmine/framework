<?php

namespace RedJasmine\Payment\Domain\Services\Routing;

use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductTypeEnum;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelProductMode;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Services\ChannelAppPermissionService;

/**
 * 转账路由服务
 */
class TransferRoutingService
{

    public function __construct(
        protected ChannelAppPermissionService $channelAppPermissionService,
        protected MerchantAppRepositoryInterface $merchantAppRepository,

    ) {
    }

    /**
     * @param  Transfer  $transfer
     *
     * @return ChannelApp|null
     * @throws PaymentException
     */
    public function getChannelApp(Transfer $transfer) : ?ChannelApp
    {
        $environment               = new TransferEnvironment();
        $environment->methodCode   = $transfer->method_code;
        $environment->sceneCode    = $transfer->scene_code;
        $environment->channelAppId = $transfer->channel_app_id;

        $merchantApp = $this->merchantAppRepository->find($transfer->merchant_app_id);

        $availableChannelApps = $this->channelAppPermissionService
            ->getAvailableChannelAppsByMerchantApp($merchantApp->id);


        // 转账环境过滤
        $availableChannelApps = collect($availableChannelApps)
            ->filter(function (ChannelApp $channelApp) use ($environment) {
                return $this->channelAppEnvironmentFilter($environment, $channelApp);
            })->all();

        $availableChannelApps = collect($availableChannelApps);


        if ($availableChannelApps->count() <= 0) {
            throw new PaymentException('渠道路由异常', PaymentException::CHANNEL_ROUTE);
        }

        // 如果选中了支付渠道 查看是否存在合适的应用
        if ($environment->channelAppId) {
            $availableChannelApps = $availableChannelApps->filter(function (ChannelApp $channelApp) use ($environment) {
                return $channelApp->channel_app_id === $environment->channelAppId;
            });
        }

        // 根据支付方式 ，选址可选的支付应用
        return collect($availableChannelApps)->random(1)->first();


    }

    protected function channelAppEnvironmentFilter(TransferEnvironment $environment, ChannelApp $channelApp) : bool
    {
        // 签约的产品
        $channelProducts = $channelApp
            ->products
            ->filter(function (ChannelProduct $channelProduct) use ($environment) {

                return $this->channelProductEnvironmentFilter($channelProduct, $environment);
            })->all();


        return collect($channelProducts)->count() > 0;
    }

    protected function channelProductEnvironmentFilter(
        ChannelProduct $channelProduct,
        TransferEnvironment $environment
    ) : bool {
        if (!$channelProduct->isAvailable()) {
            return false;
        }
        $isAvailable = false;


        // 产品类型需要支持
        if ($channelProduct->type !== ChannelProductTypeEnum::TRANSFER) {
            return false;
        }

        // 模式需要支持
        foreach ($channelProduct->modes as $channelProductMode) {
            if ($this->isModeAvailable($channelProductMode, $environment)) {
                $isAvailable = true;
            }
        }
        return $isAvailable;
    }


    protected function isModeAvailable(ChannelProductMode $channelProductMode, TransferEnvironment $environment) : bool
    {
        // 模式状态 启用
        if ($channelProductMode->isEnabled() === false) {
            return false;
        }
        // 满足支付方式一致
        if ($channelProductMode->method_code !== $environment->methodCode) {
            return false;
        }

        return true;

    }


    /**
     * @param  Transfer  $transfer
     * @param  ChannelApp  $channelApp
     *
     * @return ChannelProduct
     * @throws PaymentException
     */
    public function getChannelProduct(Transfer $transfer, ChannelApp $channelApp) : ChannelProduct
    {

        $environment               = new TransferEnvironment();
        $environment->methodCode   = $transfer->method_code;
        $environment->sceneCode    = $transfer->scene_code;
        $environment->channelAppId = $transfer->channel_app_id;


        $channelProducts = $channelApp
            ->products
            ->filter(function (ChannelProduct $channelProduct) use ($environment) {
                return $this->channelProductEnvironmentFilter($channelProduct, $environment);
            })->all();
        if (collect($channelProducts)->count() <= 0) {
            throw PaymentException::newFromCodes(PaymentException::CHANNEL_PRODUCT_ROUTE);
        }
        return collect($channelProducts)->random(1)->first();
    }

}
