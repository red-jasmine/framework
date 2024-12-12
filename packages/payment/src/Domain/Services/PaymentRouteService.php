<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Support\Collection;
use RedJasmine\Payment\Domain\Data\PaymentEnvironmentData;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;
use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelProductMode;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

/**
 * 交易路由
 */
class PaymentRouteService
{


    public array $modes = [];

    /**
     * 获取支付渠道
     * @param Trade $trade
     * @param Environment $environment
     * @return Collection
     */
    public function getMethods(Trade $trade, Environment $environment) : Collection
    {
        // 获取当前 商户应用 允许的 渠道应用列表、根据应用开通的产品、产品支付的方式 枚举出所有的支付方式
        $merchantApp = $trade->merchantApp;
        // 获取应用商户
        $merchant = $merchantApp->merchant;
        // 获取可用的 渠道应用
        return $this->getChannelAppsMethods($environment, $merchant->getAvailableChannelApps());
    }


    /**
     * @param Trade $trade
     * @param Environment $environment
     * @return ChannelApp|null
     */
    public function getChannelApp(Trade $trade, Environment $environment) : ?ChannelApp
    {
        // 根据选择的  支付方式、支付场景
        $merchantApp = $trade->merchantApp;
        $merchant    = $merchantApp->merchant;
        // 获取可选的渠道应用
        // 可用的支付应用
        $availableChannelApps = $merchant->getAvailableChannelApps();
        // 选定 一个支付应用
        $availableChannelApps = collect($availableChannelApps)->filter(function (ChannelApp $channelApp) use ($environment) {
            return $this->channelAppEnvironmentFilter($channelApp, $environment);
        })->all();


        dd($availableChannelApps);


    }

    protected function channelAppEnvironmentFilter(Environment $environment, ChannelApp $channelApp) : bool
    {
        // 过滤渠道应用
        $isAvailable = false;
        // 判断当前渠道应用是否满足环境要求

        // 签约的产品
        $channelApp->products
            ->each(function (ChannelProduct $channelProduct) use ($environment, &$isAvailable) {
                if ($isAvailable) {
                    return;
                }
                // 产品支付的支付方式 和 场景
                $channelProduct->modes->each(function (ChannelProductMode $channelProductMode) use ($environment, &$isAvailable) {
                    if ($isAvailable) {
                        return;
                    }
                    if ($this->isModeAvailabel($channelProductMode, $environment)) {
                        $isAvailable = true;
                    }

                });

            });

        return $isAvailable;
    }

    protected function isModeAvailabel(ChannelProductMode $channelProductMode, Environment $environment) : bool
    {
        // 满足 场景 一致
        if ($channelProductMode->scene_code !== $environment->scene) {
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
        // 支付方式启用

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

        $modes = $modes->where('scene_code', $environment->scene->value)->all();

        // TODO 根据自定义策略 callback
        // 根据 设备、客户端、SDK 更加明细地筛选出 可用的支付方式

        // 返回所有的支付方式
        $methods = [];
        /**
         * @var $modes ChannelProductMode[]
         */
        foreach ($modes as $mode) {
            // 如果当前模式是开启的
            if ($mode->status === ModeStatusEnum::ENABLE) {
                $methods[$mode->method_code] = $mode->method;
            } else if (!isset($methods[$mode->method_code])) {
                $mode->method->status        = ModeStatusEnum::DISABLED;
                $methods[$mode->method_code] = $mode->method;
            }

        }

        // 根据 支付场景 选择 支付
        return Collection::make(array_values($methods));

    }

}
