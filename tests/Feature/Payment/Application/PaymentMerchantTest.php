<?php

use RedJasmine\Payment\Application\Services\Channel\ChannelCommandService;
use RedJasmine\Payment\Application\Services\ChannelApp\ChannelAppCommandService;
use RedJasmine\Payment\Application\Services\ChannelProduct\ChannelProductApplicationService;
use RedJasmine\Payment\Application\Services\Merchant\Commands\MerchantCreateCommand;
use RedJasmine\Payment\Application\Services\Merchant\Commands\MerchantSetStatusCommand;
use RedJasmine\Payment\Application\Services\Merchant\Commands\MerchantUpdateCommand;
use RedJasmine\Payment\Application\Services\Merchant\MerchantCommandService;
use RedJasmine\Payment\Application\Services\Method\MethodApplicationService;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;
use RedJasmine\Tests\Feature\Payment\Fixtures\BaseDataFixtures;

beforeEach(function () {

    BaseDataFixtures::init($this);
    $this->paymentMerchantRepository     = app(MerchantRepositoryInterface::class);
    $this->paymentMerchantCommandService = app(MerchantCommandService::class);

    $this->channelCommandService = app(ChannelCommandService::class);
    $this->channelRepository     = app(ChannelRepositoryInterface::class);


    $this->channelAppCommandService = app(ChannelAppCommandService::class);
    $this->channelAppRepository     = app(ChannelAppRepositoryInterface::class);
    //


    $this->ChannelCommandService = app(ChannelCommandService::class);
    $this->ChannelRepository     = app(ChannelRepositoryInterface::class);


    $this->methodRepository     = app(MethodRepositoryInterface::class);
    $this->methodCommandService = app(MethodApplicationService::class);


    $this->productCommandService = app(ChannelProductApplicationService::class);
    $this->productRepository     = app(ChannelProductRepositoryInterface::class);


    //
});


test('can create merchant', function () {

    $command = new MerchantCreateCommand();

    $command->owner     = \Illuminate\Support\Facades\Auth::user();
    $command->name      = 'XXX有限公司';
    $command->shortName = '测试';
    $merchant           = $this->paymentMerchantCommandService->create($command);
    $this->assertEquals($command->name, $merchant->name, '商户名称');

    return $merchant;
});


test('can set status', function (Merchant $merchant) {
    $command         = new MerchantSetStatusCommand();
    $command->id     = $merchant->id;
    $command->status = MerchantStatusEnum::DISABLE;
    $this->paymentMerchantCommandService->setStatus($command);

    $merchant = $this->paymentMerchantRepository->find($command->id);

    $this->assertEquals($command->status->value, $merchant->status->value, '商户状态');
    return $merchant;
})->depends('can create merchant');


test('can update merchant', function (Merchant $merchant) {

    $command     = new MerchantUpdateCommand();
    $command->setKey($merchant->id);

    $command->owner = \Illuminate\Support\Facades\Auth::user();

    $command->name      = 'XXXX有限公司';
    $command->shortName = '测试';


    $merchant = $this->paymentMerchantCommandService->update($command);

    $this->assertEquals($command->name, $merchant->name, '商户名称');

    return $merchant;
})->depends('can create merchant');

