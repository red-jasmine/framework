<?php

namespace RedJasmine\Payment\Application\Services\Payer\Commands;

use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Services\ChannelAppPermissionService;
use RedJasmine\Payment\Domain\Services\PayerService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class PayerLoginCommandHandler extends CommandHandler
{

    public function __construct(
        protected MerchantAppRepositoryInterface $merchantAppRepository,
        protected ChannelAppPermissionService $channelAppPermissionService,
        protected PayerService $payerService) {
    }


    public function handle(PayerLoginCommand $command) : Payer
    {

        // 查询应用
        $merchantApp = $this->merchantAppRepository->find($command->merchantAppId);

        // 根据应用查询授权
        $availableChannelApps = $this->channelAppPermissionService->getAvailableChannelAppsByMerchantApp($merchantApp->id);

        // 验证 渠道ID 是否有授权
        $availableChannelApp = $availableChannelApps->where('channel_app_id', $command->channelAppId)->first();

        // 根据当前环境，当前应用的授权
        return $this->payerService->login($availableChannelApp, $command->code);

    }
}
