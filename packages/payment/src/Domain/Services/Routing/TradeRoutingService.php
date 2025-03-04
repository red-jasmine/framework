<?php

namespace RedJasmine\Payment\Domain\Services\Routing;

use Illuminate\Support\Collection;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\ClientTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelProductMode;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Services\ChannelAppPermissionService;

/**
 * 交易 路由器
 */
class TradeRoutingService
{

    public function __construct(
        protected ChannelAppPermissionService $channelAppPermissionService,
        protected MerchantAppRepositoryInterface $merchantAppRepository,

    ) {
    }

    protected array $modes = [];

    /**
     * 获取支付渠道
     *
     * @param  Trade  $trade
     * @param  Environment  $environment
     *
     * @return Collection
     */
    public function getMethods(Trade $trade, Environment $environment) : Collection
    {
        // 获取当前 商户应用 允许的 渠道应用列表、根据应用开通的产品、产品支付的方式 枚举出所有的支付方式
        $merchantApp = $trade->merchantApp;
        // 获取应用商户
        $merchant = $merchantApp->merchant;
        // 获取可用的 渠道应用
        $availableChannelApps = $this->channelAppPermissionService->getAvailableChannelAppsByMerchantApp($merchantApp->id);

        return $this->getChannelAppsMethods($environment, $availableChannelApps);
    }


    /**
     * @param  Trade  $trade
     * @param  Environment  $environment
     *
     * @return ChannelApp
     * @throws PaymentException
     */
    public function getChannelApp(Trade $trade, Environment $environment) : ChannelApp
    {
        // 根据选择的  支付方式、支付场景
        $merchantApp          = $trade->merchantApp;
        $availableChannelApps = $this->channelAppPermissionService->getAvailableChannelAppsByMerchantApp($merchantApp->id);

        // 过滤
        $availableChannelApps = collect($availableChannelApps)
            ->filter(function (ChannelApp $channelApp) use ($environment) {
                return $channelApp->isAvailable() && $this->channelAppEnvironmentFilter($environment, $channelApp);
            })
            ->all();

        $availableChannelApps = collect($availableChannelApps);
        if ($availableChannelApps->count() <= 0) {
            throw PaymentException::newFromCodes(PaymentException::CHANNEL_ROUTE);
        }
        // TODO 路由渠道
        // 最终返回随机一个渠道应用
        return collect($availableChannelApps)->random(1)->first();


    }


    /**
     * @param  Environment  $environment
     * @param  ChannelApp  $channelApp
     *
     * @return ChannelProduct
     * @throws PaymentException
     */
    public function getChannelProduct(Environment $environment, ChannelApp $channelApp) : ChannelProduct
    {
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

    protected function channelProductEnvironmentFilter(ChannelProduct $channelProduct, Environment $environment) : bool
    {
        if (!$channelProduct->isAvailable()) {
            return false;
        }
        $isAvailable = false;

        if ($channelProduct->type !== ChannelProductTypeEnum::PAYMENT) {
            return false;
        }

        foreach ($channelProduct->modes as $channelProductMode) {

            if ($this->isModeAvailable($channelProductMode, $environment)) {
                $isAvailable = true;
            }
        }
        return $isAvailable;
    }

    protected function channelAppEnvironmentFilter(Environment $environment, ChannelApp $channelApp) : bool
    {

        // 签约的产品
        $channelProducts = $channelApp
            ->products
            ->filter(function (ChannelProduct $channelProduct) use ($environment) {
                return $this->channelProductEnvironmentFilter($channelProduct, $environment);
            })
            ->all();

        return collect($channelProducts)->count() > 0;
    }

    protected function isModeAvailable(ChannelProductMode $channelProductMode, Environment $environment) : bool
    {
        // 满足 场景 一致
        if ($channelProductMode->scene_code !== $environment->scene->value) {
            return false;
        }
        // 满足支付方式一致
        if ($channelProductMode->method_code !== $environment->method) {
            return false;
        }
        // 模式状态 启用
        if ($channelProductMode->isEnabled() === false) {
            return false;
        }
        return true;

    }


    protected function getChannelAppsMethods(Environment $environment, $availableChannelApps) : Collection
    {
        /**
         * @var $modes ChannelProductMode[]
         */
        $modes = Collection::make();

        foreach ($availableChannelApps as $availableChannelApp) {
            // 获取应用签约的产品、
            // 获取产品支持的支付方式和渠道
            $availableChannelApp->products->each(function ($product) use ($modes) {
                $product->modes->each(function (ChannelProductMode $mode) use ($modes) {
                    // 获取支付方式
                    $modes->push($mode);
                });
            });
        }
        // 根据场景查询可用的 支付模式

        $modes = $modes->where('scene_code', $environment->scene->value);

        // TODO 过滤器
        // 根据 设备、客户端、SDK 更加明细地筛选出 可用的支付方式

        if ($environment->client?->type === ClientTypeEnum::APPLET) {
            $modes = $modes->filter(function (ChannelProductMode $mode)use($environment) {
                return $environment->client->platform === $mode->method_code;
            });
        }

        // 返回所有的支付方式
        $methods = [];
        /**
         * @var $modes ChannelProductMode[]
         */
        foreach ($modes as $mode) {
            // 如果当前模式是开启的
            if ($mode->status === ModeStatusEnum::ENABLE) {
                $methods[$mode->method_code] = $mode->method;
            } else {
                if (!isset($methods[$mode->method_code])) {
                    $mode->method->status        = ModeStatusEnum::DISABLE;
                    $methods[$mode->method_code] = $mode->method;
                }
            }

        }

        // 根据 支付场景 选择 支付
        return Collection::make(array_values($methods));

    }

}
