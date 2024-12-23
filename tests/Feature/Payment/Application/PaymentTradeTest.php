<?php


use Illuminate\Support\Collection;
use RedJasmine\Payment\Application\Commands\Trade\TradePaidCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradePayingCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradePreCreateCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\TradeCommandService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\ClientTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelAppProduct;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelProductMode;
use RedJasmine\Payment\Domain\Models\ValueObjects\Client;
use RedJasmine\Payment\Domain\Models\ValueObjects\Device;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;

beforeEach(function () {
    // 数据准备

    /**
     * @var Merchant $merchant
     */
    $this->merchant = Merchant::firstOrCreate(
        [
            'name' => '测试商户',
        ],
        [
            'owner_type' => 'user',
            'owner_id'   => 1,
            'status'     => MerchantStatusEnum::ENABLE->value,
            'name'       => '测试商户',
            'short_name' => '测试',
            'type'       => MerchantTypeEnum::GENERAL->value,
        ]);


    $this->merchantApp = MerchantApp::firstOrCreate(
        [
            'merchant_id' => $this->merchant->id,
            'name'        => '测试应用',
        ],
        [
            'merchant_id' => $this->merchant->id,
            'name'        => '测试应用',
            'status'      => MerchantAppStatusEnum::ENABLE->value
        ],
    );

    // 支付方式
    $this->paymentMethods[] = Method::firstOrCreate(
        ['code' => 'alipay'],
        ['name' => '支付宝', 'code' => 'alipay']

    );
    $this->paymentMethods[] = Method::firstOrCreate(
        ['code' => 'wechat'],
        ['name' => '微信', 'code' => 'wechat'],

    );

    //  支付渠道

    $this->channels[] = Channel::firstOrCreate(
        ['code' => 'alipay'],
        ['name' => '支付宝', 'code' => 'alipay']
    );

    $this->channels[] = Channel::firstOrCreate(
        ['code' => 'wechat'],
        ['name' => '微信', 'code' => 'wechat']
    );

    // 创建产品

    $productsData          = [
        [
            'channel_code' => 'alipay',
            'code'         => 'FACE_TO_FACE_PAYMENT',
            'name'         => '当面付',
            'gateway'      => 'Alipay_AopF2F',
            'modes'        => [
                [
                    'scene_code'  => SceneEnum::FACE,
                    'method_code' => 'alipay'
                ],
                [
                    'scene_code'  => SceneEnum::QRCODE,
                    'method_code' => 'alipay'
                ],
            ],
        ],
        [
            'channel_code' => 'alipay',
            'code'         => 'JSAPI',
            'name'         => '小程序支付',
            'gateway'      => 'Alipay_AopJs',
            'modes'        => [
                [
                    'scene_code'  => SceneEnum::JSAPI,
                    'method_code' => 'alipay'
                ],
            ],
        ],
        [
            'channel_code' => 'alipay',
            'code'         => 'QUICK_MSECURITY_PAY',
            'name'         => 'APP支付',
            'gateway'      => 'Alipay_AopApp',
            'modes'        => [
                [
                    'scene_code'  => SceneEnum::APP,
                    'method_code' => 'alipay'
                ],
            ],
        ],
        [
            'channel_code' => 'alipay',
            'code'         => 'QUICK_WAP_WAY',
            'name'         => '手机网站支付',
            'gateway'      => 'Alipay_AopWap',
            'modes'        => [
                [
                    'scene_code'  => SceneEnum::WAP,
                    'method_code' => 'alipay'
                ],
            ],
        ],

        [
            'channel_code' => 'alipay',
            'code'         => 'FAST_INSTANT_TRADE_PAY',
            'name'         => '电脑网站支付',
            'gateway'      => 'Alipay_AopPage',
            'modes'        => [
                [
                    'scene_code'  => SceneEnum::WEB,
                    'method_code' => 'alipay'
                ],
            ],
        ],

    ];
    $this->channelProducts = [];
    // 设置渠道产品的 支持的支付模式
    foreach ($productsData as $productData) {
        $this->channelProducts[] = $channelProduct = ChannelProduct::firstOrCreate(
            [
                'channel_code' => $productData['channel_code'],
                'code'         => $productData['code'],
            ],
            $productData
        );

        foreach ($productData['modes'] as $mode) {
            ChannelProductMode::firstOrCreate([
                'payment_channel_product_id' => $channelProduct->id,
                'method_code'                => $mode['method_code'],
                'scene_code'                 => $mode['scene_code']
            ], [
                'payment_channel_product_id' => $channelProduct->id,
                'method_code'                => $mode['method_code'],
                'scene_code'                 => $mode['scene_code']
            ]);
        }
    }


    $channelAppsData   = [
        [
            'owner_type'     => 'user',
            'owner_id'       => 1,
            'channel_code'   => 'alipay',
            'channel_app_id' => '2021003187696362',
            'name'           => '测试应用1',
        ]
    ];
    $this->channelApps = [];
    foreach ($channelAppsData as $channelAppData) {
        $this->channelApps[] = $channelApp = ChannelApp::firstOrCreate(
            \Illuminate\Support\Arr::only($channelAppData, [
                'owner_type',
                'owner_id',
                'channel_code',
                'channel_app_id'
            ]),
            $channelAppData
        );
        // 设置应用签约的产品
        foreach ($this->channelProducts as $channelProduct) {
            if ($channelApp->channel_code === $channelProduct->channel_code) {
                ChannelAppProduct::firstOrCreate([
                    'payment_channel_product_id' => $channelProduct->id,
                    'payment_channel_app_id'     => $channelApp->id,
                ], [
                    'payment_channel_product_id' => $channelProduct->id,
                    'payment_channel_app_id'     => $channelApp->id,
                ]);
            }
        }
    }


    //  给商户授权 渠道应用


    $this->merchant->channelApps()->sync(collect($this->channelApps)->pluck('id')->toArray());


    $this->tradeCommandService = app(TradeCommandService::class);
    $this->tradeRepository     = app(TradeRepositoryInterface::class);

});

test('pre create a payment trade', function () {


    $command = new  TradePreCreateCommand();

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


    $trade = $this->tradeCommandService->preCreate($command);


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

    dd($channelTrade->purchaseResult);

    $this->assertEquals($command->scene->value, $channelTrade->sceneCode, '支付场景不一致');
    $this->assertEquals($command->method, $channelTrade->methodCode, '支付方式不一致');

    // 查询交易单
    $trade = $this->tradeRepository->find($trade->id);

    $this->assertEquals($channelTrade->channelCode, $trade->channel_code, '支付单号不一致');
    $this->assertEquals($channelTrade->sceneCode, $trade->scene_code, '支付场景');
    $this->assertEquals($channelTrade->methodCode, $trade->method_code, '支付方式不一致');


    return $trade;


})->depends('pre create a payment trade', 'can get trade pay methods');
return;

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


    $this->expectException(PaymentException::class);

    $this->tradeCommandService->paid($channelTradeData);


})->depends('can paying a trade');
