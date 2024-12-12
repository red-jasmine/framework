<?php


use Illuminate\Support\Collection;
use RedJasmine\Payment\Application\Commands\Trade\TradePayingCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradePreCreateCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\TradeCommandService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
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
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;

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
        [ 'code' => 'alipay' ],
        [ 'name' => '支付宝', 'code' => 'alipay' ]

    );
    $this->paymentMethods[] = Method::firstOrCreate(
        [ 'code' => 'wechat' ],
        [ 'name' => '微信', 'code' => 'wechat' ],

    );

    //  支付渠道

    $this->channels[] = Channel::firstOrCreate(
        [ 'code' => 'alipay' ],
        [ 'name' => '支付宝', 'code' => 'alipay' ]
    );

    $this->channels[] = Channel::firstOrCreate(
          [ 'name' => '微信', 'code' => 'wechat' ]
        , [ 'code' => 'wechat' ]);

    // 创建产品

    $productsData          = [
        [
            'channel_code' => 'alipay',
            'code'         => 'FACE_TO_FACE_PAYMENT',
            'name'         => '当面付',
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
            'modes'        => [
                [
                    'scene_code'  => SceneEnum::WAP,
                    'method_code' => 'alipay'
                ],
            ],
        ],
        [
            'channel_code' => 'alipay',
            'code'         => 'QUICK_WAP_WAY',
            'name'         => '手机网站支付',
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
            [
                'channel_code' => $productData['channel_code'],
                'code'         => $productData['code'],
                'name'         => $productData['name'],
            ],
        );

        foreach ($productData['modes'] as $mode) {
            ChannelProductMode::firstOrCreate([
                                                  'payment_channel_product_id' => $channelProduct->id,
                                                  'method_code'                => $mode['method_code'],
                                                  'scene_code'                 => $mode['scene_code']
                                              ], [ 'payment_channel_product_id' => $channelProduct->id,
                                                   'method_code'                => $mode['method_code'],
                                                   'scene_code'                 => $mode['scene_code'] ]);
        }
    }


    $channelAppsData   = [
        [
            'owner_type'     => 'user',
            'owner_id'       => 1,
            'channel_code'   => 'alipay',
            'channel_app_id' => '2016101100000000000000000001',
            'name'           => '测试应用1',
        ],
        [
            'owner_type'     => 'user',
            'owner_id'       => 1,
            'channel_code'   => 'alipay',
            'channel_app_id' => '2016101100000000000000000002',
            'name'           => '测试应用2',
        ],
    ];
    $this->channelApps = [];
    foreach ($channelAppsData as $channelAppData) {
        $this->channelApps[] = $channelApp = ChannelApp::firstOrCreate(
            \Illuminate\Support\Arr::only($channelAppData, [
                'owner_type',
                'owner_id',
                'channel_code',
                'channel_app_id' ]),
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

});

test('pre create a payment trade', function () {


    $command = new  TradePreCreateCommand();

    $command->merchantAppId = $this->merchantApp->id;

    $command->amount          = Money::from([ 'amount' => 1, 'currency' => 'CNY' ]);
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


    $trade = $this->tradeCommandService->preCreate($command);


    $this->assertEquals($trade->merchant_app_id, $command->merchantAppId, '商户应用id不一致');
    $this->assertEquals($trade->amount_currency, $command->amount->currency, '货币不一致');
    $this->assertEquals($trade->amount_value, $command->amount->amount, '金额不一致');
    $this->assertEquals($trade->merchant_order_no, $command->merchantOrderNo, '商户订单号不一致');
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

    $command         = new TradeReadyCommand();
    $command->id     = $trade->id;
    $command->scene  = SceneEnum::APP;
    $command->device = 'mobile';
    $command->client = 'alipay-ios-app';

    $methods = $this->tradeCommandService->ready($command);

    $this->assertEquals($methods instanceof Collection, true, '返回值类型错误');

    return $methods;
})->depends('pre create a payment trade');

// 测试发起支付

test('can paying a trade', function (Trade $trade, $methods) {
    $command     = new TradePayingCommand();
    $command->id = $trade->id;

    $command->scene  = SceneEnum::APP;
    $command->device = 'mobile';
    $command->client = 'alipay-ios-app';
    $command->method = 'alipay';
    // TODO


})->depends('pre create a payment trade', 'can get trade pay methods');
