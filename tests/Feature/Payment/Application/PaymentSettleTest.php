<?php


use Illuminate\Support\Collection;
use RedJasmine\Payment\Application\Services\SettleReceiver\SettleReceiverCommandService;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePaidCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePayingCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePreCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeCommandService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Data\SettleReceiverData;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\ClientTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Client;
use RedJasmine\Payment\Domain\Models\ValueObjects\Device;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;
use RedJasmine\Tests\Feature\Payment\Fixtures\BaseDataFixtures;

beforeEach(function () {
    // 数据准备


    BaseDataFixtures::init($this);

    $this->tradeCommandService = app(TradeCommandService::class);
    $this->tradeRepository     = app(TradeRepositoryInterface::class);


    $this->settleReceiverCommandService = app(SettleReceiverCommandService::class);
    $this->settleReceiverRepository     = app(SettleReceiverRepositoryInterface::class);
    $this->settleReceiverReadRepository = app(SettleReceiverReadRepositoryInterface::class);

});


// 绑定分账关系

test('create settle accounts', function () {

    // 无法知晓使用哪个同步渠道的分账关系
    $command                      = new  SettleReceiverData();
    $command->systemMerchantAppId = $this->merchantApp->id;
    $accounts                     = BaseDataFixtures::settleReceivers();
    foreach ($accounts as $account) {
        // 创建或者更新
        $command->receiverType      = $account['receiver_type'];
        $command->receiverId        = $account['receiver_id'];
        $command->channelCode       = $account['channel_code'];
        $command->channelMerchantId = $account['channel_merchant_id'];
        $command->name              = $account['name'];
        $command->accountType       = AccountTypeEnum::tryFrom($account['account_type']);
        $command->account           = $account['account'];
        $command->certType          = CertTypeEnum::tryFrom($account['cert_type']);
        $command->certNo            = $account['cert_no'];

        // 查询是否存在
        $result = $this->settleReceiverReadRepository->findByMerchantAppReceiver(
            $command->systemMerchantAppId,
            $command->receiverType,
            $command->receiverId,
            $command->channelCode,
            $command->channelMerchantId,
        );
        if (!$result) {
            $result = $this->settleReceiverCommandService->create($command);
        }
        $this->assertEquals($command->receiverId, $result->receiver_id);
        $this->assertEquals($command->receiverType, $result->receiver_type);
        $this->assertEquals($command->account, $result->account);


    }

});


test('pre create a payment trade', function () {


    $command = new  TradePreCreateCommand();
    // 设置为需要 分账
    $command->isSettleSharing = true;
    $command->merchantAppId   = $this->merchantApp->id;

    $command->amount               = Money::from([ 'value' => fake()->numberBetween(1000, 5000), 'currency' => 'CNY' ]);
    $command->merchantTradeNo      = fake()->numerify('trade-no-##########');
    $command->merchantTradeOrderNo = fake()->numerify('order-no-##########');
    $command->subject              = '测试支付';
    $command->description          = '支付描述';
    $command->goodDetails          = GoodDetailData::collect([
                                                                 [
                                                                     'goods_name' => fake()->word(),
                                                                     'price'      => [
                                                                         'currency' => 'CNY',
                                                                         'value'    => fake()->numberBetween(2, 90),
                                                                     ],
                                                                     'quantity'   => fake()->numberBetween(1, 10),
                                                                     'goods_id'   => fake()->numerify('goods-id-########'),
                                                                     'category'   => fake()->word(),
                                                                 ],
                                                                 [
                                                                     'goods_name' => fake()->word(),
                                                                     'price'      => [
                                                                         'currency' => 'CNY',
                                                                         'value'    => fake()->numberBetween(2, 90),
                                                                     ],
                                                                     'quantity'   => fake()->numberBetween(1, 10),
                                                                     'goods_id'   => fake()->numerify('goods-id-########'),
                                                                     'category'   => fake()->word(),
                                                                 ],
                                                             ]);


    $trade = $this->tradeCommandService->preCreate($command);


    $this->assertEquals($command->merchantAppId, $trade->merchant_app_id, '商户应用id不一致');
    $this->assertEquals($command->amount->currency, $trade->amount_currency, '货币不一致');
    $this->assertEquals($command->amount->value, $trade->amount_value, '金额不一致');
    $this->assertEquals($command->merchantTradeNo, $trade->merchant_trade_no, '商户单号不一致');
    $this->assertEquals($command->merchantTradeOrderNo, $trade->merchant_trade_order_no, '商户原始订单号不一致');
    $this->assertEquals($command->subject, $trade->subject, '订单主题不一致');
    $this->assertEquals($command->description, $trade->description, '订单描述不一致');
    $this->assertEquals(TradeStatusEnum::PRE->value, $trade->status->value, '订单状态不一致');
    $this->assertEquals(true, $trade->is_settle_sharing, '是否结算分账不对');


    // 再次创建一定报错
    //$this->expectException(Throwable::class);
    //$this->tradeCommandService->preCreate($command);


    return $trade;

});

// 查询取支付方式

test('can get trade pay methods', function (Trade $trade) {

    $command                = new TradeReadyCommand();
    $command->merchantAppId = $trade->merchant_app_id;
    $command->tradeNo       = $trade->trade_no;

    $command->scene  = SceneEnum::APP;
    $command->method = 'alipay';
    $command->device = Device::from([
                                        'id'         => fake()->uuid(),
                                        'model'      => fake()->uuid(),
                                        'os'         => fake()->randomElement([ 'ios', 'android' ]),
                                        'brand'      => fake()->randomElement([ 'apple', 'huawei', 'xiaomi' ]),
                                        'version'    => fake()->randomElement([ '9.0.0', '10.0.0', '11.0.0' ]),
                                        'token'      => fake()->uuid(),
                                        'language'   => fake()->randomElement([ 'zh-CN', 'en-US' ]),
                                        'extensions' => '{sss: "sss"}'
                                    ]);

    $command->client = Client::from([
                                        'name'    => fake()->randomElement([ 'alipay', 'wechat' ]),
                                        'type'    => fake()->randomElement(ClientTypeEnum::values()),
                                        'ip'      => fake()->ipv4(),
                                        'version' => fake()->numerify('v#.##.###'),
                                        'agent'   => fake()->userAgent(),
                                    ]);

    $methods = $this->tradeCommandService->ready($command);

    $this->assertEquals($methods instanceof Collection, true, '返回值类型错误');

    return $methods;
})->depends('pre create a payment trade');

// 测试发起支付

test('can paying a trade', function (Trade $trade, $methods) {
    $command                = new TradePayingCommand();
    $command->merchantAppId = $trade->merchant_app_id;
    $command->tradeNo       = $trade->trade_no;

    $command->scene  = SceneEnum::WEB;
    $command->method = 'alipay';
    $command->device = Device::from([
                                        'id'         => fake()->uuid(),
                                        'model'      => fake()->uuid(),
                                        'os'         => fake()->randomElement([ 'ios', 'android' ]),
                                        'brand'      => fake()->randomElement([ 'apple', 'huawei', 'xiaomi' ]),
                                        'version'    => fake()->randomElement([ '9.0.0', '10.0.0', '11.0.0' ]),
                                        'token'      => fake()->uuid(),
                                        'language'   => fake()->randomElement([ 'zh-CN', 'en-US' ]),
                                        'extensions' => json_encode([ 'ss' => 'ss' ], JSON_THROW_ON_ERROR)
                                    ]);
    $command->client = Client::from([
                                        'name'    => fake()->randomElement([ 'alipay', 'wechat' ]),
                                        'type'    => fake()->randomElement(ClientTypeEnum::values()),
                                        'ip'      => fake()->ipv4(),
                                        'version' => fake()->numerify('v#.##.###'),
                                        'agent'   => fake()->userAgent(),
                                    ]);


    $channelTrade = $this->tradeCommandService->paying($command);



    $this->assertEquals($command->scene->value, $channelTrade->sceneCode, '支付场景不一致');
    $this->assertEquals($command->method, $channelTrade->methodCode, '支付方式不一致');

    // 查询交易单
    $trade = $this->tradeRepository->find($trade->id);

    $this->assertEquals($channelTrade->channelCode, $trade->channel_code, '支付单号不一致');
    $this->assertEquals($channelTrade->sceneCode, $trade->scene_code, '支付场景');
    $this->assertEquals($channelTrade->methodCode, $trade->method_code, '支付方式不一致');


    return $trade;


})->depends('pre create a payment trade', 'can get trade pay methods');


test('can paid a trade', function (Trade $trade) {

    $channelTradeData = new TradePaidCommand();

    $channelTradeData->tradeNo           = $trade->trade_no;
    $channelTradeData->channelTradeNo    = fake()->numerify('channel-trade-no-##########');
    $channelTradeData->channelCode       = 'alipay';
    $channelTradeData->channelAppId      = fake()->numerify('channelAppId-##########');
    $channelTradeData->channelMerchantId = fake()->numerify('channelMerchantId-##########');
    $channelTradeData->paidTime          = now();
    $channelTradeData->paymentAmount     = $trade->amount;
    $channelTradeData->payer             = Payer::from([
                                                           'type'    => fake()->randomElement([ 'private', 'company' ]),
                                                           'name'    => fake()->name(),
                                                           'account' => fake()->numerify('account-##########'),
                                                           'openId'  => fake()->uuid(),
                                                           'userId'  => fake()->uuid(),
                                                       ]);


    $result = $this->tradeCommandService->paid($channelTradeData);

    $this->assertEquals(true, $result);

    $trade = $this->tradeRepository->findByNo($trade->trade_no);

    $this->assertEquals(TradeStatusEnum::SUCCESS, $trade->status, '支付状态不一致');
    $this->assertEquals($channelTradeData->channelTradeNo, $trade->channel_trade_no, '支付单号不一致');
    $this->assertEquals($channelTradeData->channelCode, $trade->channel_code, '支付渠道不一致');


    $this->expectException(PaymentException::class);

    $this->tradeCommandService->paid($channelTradeData);


})->depends('can paying a trade');
