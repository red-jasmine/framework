<?php


use RedJasmine\Payment\Application\Services\Trade\TradeCommandService;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCreateCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferExecutingCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferFailCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferSuccessCommand;
use RedJasmine\Payment\Application\Services\Transfer\TransferCommandService;
use RedJasmine\Payment\Domain\Data\TransferPayee;
use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Tests\Feature\Payment\Fixtures\MerchantFixtures;

beforeEach(function () {
    // 数据准备

    MerchantFixtures::init($this);
    $this->tradeCommandService    = app(TradeCommandService::class);
    $this->tradeRepository        = app(TradeRepositoryInterface::class);
    $this->transferCommandService = app(TransferCommandService::class);
    $this->transferRepository     = app(TransferRepositoryInterface::class);

});


test('create a transfer', function () {
    $channelApp = $this->merchant->channelApps->first();

    $TransferPayee = TransferPayee::from([
        'identity_type' => 'LOGIN_ID',
        'identityId'    => 'sildsg4556@sandbox.com',
        'certNo'        => '933396192809243496',
        'certType'      => 'ID_CARD',
        'name'          => 'sildsg4556',
    ]);

    $command                     = new TransferCreateCommand();
    $command->merchantAppId      = $this->merchantApp->id;
    $command->sceneCode          = TransferSceneEnum::OTHER;
    $command->subject            = '测试转账';
    $command->amount             = Money::from(['value' => 1, 'currency' => 'CNY']);
    $command->merchantTransferNo = fake()->numerify('transfer-no-##########');
    $command->methodCode         = 'alipay';
    $command->channelAppId       = $channelApp->channel_app_id;  // 指定渠道应用
    $command->payee              = $TransferPayee;
    $result                      = $this->transferCommandService->create($command);

    $this->assertInstanceOf(Transfer::class, $result);

    $this->assertEquals($command->amount->value, $result->amount->value);

    return $result;
});

test('can executing a transfer', function (Transfer $transfer) {

    $command             = new  TransferExecutingCommand;
    $command->transferNo = $transfer->transfer_no;
    $result              = $this->transferCommandService->executing($command);
    $this->assertEquals(true, $result);
    $transfer = $this->transferRepository->findByNo($transfer->transfer_no);
    $this->assertEquals(TransferStatusEnum::PROCESSING->value, $transfer->transfer_status->value);


    return $transfer;

})->depends('create a transfer');

return;
test('can transfer fail', function (Transfer $transfer) {

    $command             = new TransferFailCommand();
    $command->transferNo = $transfer->transfer_no;
    $result              = $this->transferCommandService->fail($command);
    $this->assertEquals(true, $result);
    $transfer = $this->transferRepository->findByNo($transfer->transfer_no);
    $this->assertEquals(TransferStatusEnum::FAIL->value, $transfer->transfer_status->value);


})->depends('can executing a transfer');
test('can transfer success', function (Transfer $transfer) {

    $command                    = new TransferSuccessCommand();
    $command->transferNo        = $transfer->transfer_no;
    $command->channelTransferNo = fake()->numerify('channel-transfer-no-##########');
    $command->transferTime      = now();
    $result                     = $this->transferCommandService->success($command);
    $this->assertEquals(true, $result);
    $transfer = $this->transferRepository->findByNo($transfer->transfer_no);
    $this->assertEquals(TransferStatusEnum::SUCCESS->value, $transfer->transfer_status->value);
    $this->assertEquals($command->channelTransferNo, $transfer->channel_transfer_no);
    $this->assertEquals($command->transferTime->format('Y-m-d H:i:s'), $transfer->transfer_time);

})->depends('can executing a transfer');




