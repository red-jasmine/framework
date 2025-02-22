<?php


use Illuminate\Support\Collection;
use RedJasmine\Payment\Application\Services\Refund\Commands\RefundCreateCommand;
use RedJasmine\Payment\Application\Services\Refund\RefundCommandService;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePaidCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePayingCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeCommandService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Models\Enums\ClientTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Client;
use RedJasmine\Payment\Domain\Models\ValueObjects\Device;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;
use RedJasmine\Tests\Feature\Payment\Fixtures\BaseDataFixtures;

beforeEach(function () {
    BaseDataFixtures::init($this);
    $this->tradeCommandService  = app(TradeCommandService::class);
    $this->tradeRepository      = app(TradeRepositoryInterface::class);
    $this->refundCommandService = app(RefundCommandService::class);
    $this->refundRepository     = app(RefundRepositoryInterface::class);

});

test('pre create a payment trade', function () {


    $command = new  TradeCreateCommand();

    $command->merchantAppId = $this->merchantApp->id;

    $command->amount               = Money::from(['value' => 1, 'currency' => 'CNY']);
    $command->merchantTradeNo      = fake()->numerify('trade-no-##########');
    $command->merchantTradeOrderNo = fake()->numerify('order-no-##########');
    $command->subject              = '测试支付';
    $command->description          = '支付描述';
    $command->goodDetails          = GoodDetailData::collect([
        [
            'goods_name' => fake()->word(),
            'price'      => [
                'currency' => 'CNY',
                'value'    => fake()->randomNumber(2, 90),
            ],
            'quantity'   => fake()->randomNumber(1, 10),
            'goods_id'   => fake()->numerify('goods-id-########'),
            'category'   => fake()->word(),
        ],
        [
            'goods_name' => fake()->word(),
            'price'      => [
                'currency' => 'CNY',
                'value'    => fake()->randomNumber(2, 90),
            ],
            'quantity'   => fake()->randomNumber(1, 10),
            'goods_id'   => fake()->numerify('goods-id-########'),
            'category'   => fake()->word(),
        ],
    ]);


    $trade = $this->tradeCommandService->create($command);


    $this->assertEquals($trade->merchant_app_id, $command->merchantAppId, '商户应用id不一致');
    $this->assertEquals($trade->amount_currency, $command->amount->currency, '货币不一致');
    $this->assertEquals($trade->amount_value, $command->amount->value, '金额不一致');
    $this->assertEquals($trade->merchant_trade_no, $command->merchantTradeNo, '商户单号不一致');
    $this->assertEquals($trade->merchant_trade_order_no, $command->merchantTradeOrderNo, '商户原始订单号不一致');
    $this->assertEquals($trade->subject, $command->subject, '订单主题不一致');
    $this->assertEquals($trade->description, $command->description, '订单描述不一致');
    $this->assertEquals($trade->status->value, TradeStatusEnum::PRE->value, '订单状态不一致');


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
        'os'         => fake()->randomElement(['ios', 'android']),
        'brand'      => fake()->randomElement(['apple', 'huawei', 'xiaomi']),
        'version'    => fake()->randomElement(['9.0.0', '10.0.0', '11.0.0']),
        'token'      => fake()->uuid(),
        'language'   => fake()->randomElement(['zh-CN', 'en-US']),
        'extensions' => '{sss: "sss"}'
    ]);

    $command->client = Client::from([
        'name'    => fake()->randomElement(['alipay', 'wechat']),
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
        'os'         => fake()->randomElement(['ios', 'android']),
        'brand'      => fake()->randomElement(['apple', 'huawei', 'xiaomi']),
        'version'    => fake()->randomElement(['9.0.0', '10.0.0', '11.0.0']),
        'token'      => fake()->uuid(),
        'language'   => fake()->randomElement(['zh-CN', 'en-US']),
        'extensions' => json_encode(['ss' => 'ss'], JSON_THROW_ON_ERROR)
    ]);
    $command->client = Client::from([
        'name'    => fake()->randomElement(['alipay', 'wechat']),
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
        'type'    => fake()->randomElement(['private', 'company']),
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


    return $trade;

})->depends('can paying a trade');

// 退款
test('can create a refund', function (Trade $trade) {

    $command                        = new RefundCreateCommand();
    $command->tradeNo               = $trade->trade_no;
    $command->merchantRefundNo      = fake()->numerify('merchant-refund-no-##########');
    $command->merchantRefundOrderNo = fake()->numerify('merchant-refund-order-no-##########');
    $command->refundAmount          = $trade->amount;
    $command->refundReason          = fake()->sentence();
    $command->goodDetails           = GoodDetailData::collect([
        [
            'goods_name' => fake()->word(),
            'price'      => [
                'currency' => 'CNY',
                'value'    => fake()->randomNumber(2, 90),
            ],
            'quantity'   => fake()->randomNumber(1, 10),
            'goods_id'   => fake()->numerify('goods-id-########'),
            'category'   => fake()->word(),
        ],
        [
            'goods_name' => fake()->word(),
            'price'      => [
                'currency' => 'CNY',
                'value'    => fake()->randomNumber(2, 90),
            ],
            'quantity'   => fake()->randomNumber(1, 10),
            'goods_id'   => fake()->numerify('goods-id-########'),
            'category'   => fake()->word(),
        ],
    ]);
    $command->notifyUrl             = fake()->url();
    $command->passBackParams        = json_encode(['test' => 1], JSON_THROW_ON_ERROR);


    /**
     * @var Refund $refund
     */
    $refund = $this->refundCommandService->create($command);

    $this->assertEquals(TransferStatusEnum::PENDING->value, $refund->refund_status->value);
    $this->assertEquals($trade->trade_no, $refund->trade_no);
    $this->assertEquals($trade->id, $refund->trade_id);
    $this->assertEquals($trade->merchant_id, $refund->merchant_id);
    $this->assertEquals($trade->merchant_app_id, $refund->merchant_app_id);
    $this->assertEquals($trade->channel_code, $refund->channel_code);
    $this->assertEquals($trade->channel_trade_no, $refund->channel_trade_no);
    $this->assertEquals($trade->channel_app_id, $refund->channel_app_id);

    $this->assertEquals($command->merchantRefundNo, $refund->merchant_refund_no, '商户退款单号不一致');
    $this->assertEquals($command->refundAmount->value, $refund->refundAmount->value, '退款金额不一致');
    $this->assertEquals($command->refundReason, $refund->refund_reason, ' 退款原因不一致');
    $this->assertEquals($command->notifyUrl, $refund->extension->notify_url, ' 回调地址不一致');
    $this->assertEquals($command->passBackParams, $refund->extension->pass_back_params, '回调参数不一致');

})->depends('can paid a trade');
