<?php


use RedJasmine\Payment\Application\Commands\Trade\TradePreCreateCommand;
use RedJasmine\Payment\Application\Services\TradeCommandService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Models\MerchantApp;

beforeEach(function () {
    /**
     * @var Merchant $merchant
     */
    $this->merchant = Merchant::firstOrCreate(
        [
            'owner_type' => 'user',
            'owner_id'   => 1,
            'status'     => MerchantStatusEnum::ENABLE->value,
            'name'       => '测试商户',
            'short_name' => '测试',
            'type'       => MerchantTypeEnum::GENERAL->value,
        ], [
            'name' => '测试商户',
        ]);


    $this->merchantApp = MerchantApp::firstOrCreate([
                                                               'merchant_id' => $this->merchant->id,
                                                               'name'        => '测试应用',
                                                               'status'      => MerchantAppStatusEnum::ENABLE->value
                                                           ],
                                                    [
                                                               'merchant_id' => $this->merchant->id,
                                                               'name'        => '测试应用',
                                                           ]
    );


    $this->commandService = app(TradeCommandService::class);

});

test('pre create a payment trade', function () {


    $command                  = new  TradePreCreateCommand();
    $command->merchantAppId   = $this->merchantApp->id;
    $command->currency        = 'CNY';
    $command->amount          = 1;
    $command->merchantOrderNo = fake()->numerify('trade-##########');
    $command->subject         = '测试支付';
    $command->description     = '支付描述';
    $command->goodDetails     = GoodDetailData::collect([
                                                            [
                                                                'goods_name' => fake()->word(),
                                                                'price'      => fake()->randomFloat(2, 90, 100),
                                                                'quantity'   => fake()->randomNumber(1, 10),
                                                                'goods_id'   => fake()->numerify('goods-id-########'),
                                                                'category'   => fake()->word(),
                                                            ],
                                                            [
                                                                'goods_name' => fake()->word(),
                                                                'price'      => fake()->randomFloat(2, 90, 100),
                                                                'quantity'   => fake()->randomNumber(1, 10),
                                                                'goods_id'   => fake()->numerify('goods-id-########'),
                                                                'category'   => fake()->word(),
                                                            ],
                                                        ]);


    $trade = $this->commandService->preCreate($command);


    $this->assertEquals($trade->merchant_app_id, $command->merchantAppId, '商户应用id不一致');
    $this->assertEquals($trade->currency, $command->currency, '货币不一致');
    $this->assertEquals($trade->amount, $command->amount, '金额不一致');
    $this->assertEquals($trade->merchant_order_no, $command->merchantOrderNo, '商户订单号不一致');
    $this->assertEquals($trade->subject, $command->subject, '订单主题不一致');
    $this->assertEquals($trade->description, $command->description, '订单描述不一致');
    $this->assertEquals($trade->status->value, TradeStatusEnum::PRE->value, '订单状态不一致');

});
