<?php


use RedJasmine\Payment\Application\Services\Trade\TradeCommandService;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCreateCommand;
use RedJasmine\Payment\Application\Services\Transfer\TransferCommandService;
use RedJasmine\Payment\Domain\Data\TransferPayee;
use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelAppProduct;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelProductMode;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Tests\Feature\Payment\Fixtures\AlipayChannelAppData;

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
        [
            'channel_code' => 'alipay',
            'type'         => 'transfer',
            'code'         => 'TRANS_ACCOUNT_NO_PWD',
            'name'         => '单笔无密转账',
            'gateway'      => 'Alipay_AopPage',
            'modes'        => [
                [
                    'scene_code'  => SceneEnum::API,
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
        AlipayChannelAppData::get()
    ];
    $this->channelApps = [];
    foreach ($channelAppsData as $channelAppData) {
        $channelAppData['owner_type'] = 'user';
        $channelAppData['owner_id']   = 1;
        $this->channelApps[]          = $channelApp = ChannelApp::updateOrCreate(
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
});

