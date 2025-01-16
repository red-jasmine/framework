<?php

namespace RedJasmine\Tests\Feature\Payment\Fixtures;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelMerchant;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelAppProduct;
use RedJasmine\Payment\Domain\Models\ValueObjects\ChannelProductMode;

class BaseDataFixtures
{

    public static function init($test) : void
    {


        $test->owner = Auth::user();
        /**
         * @var Merchant $merchant
         */
        $test->merchant = Merchant::firstOrCreate(
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


        $test->merchantApp = MerchantApp::firstOrCreate(
            [
                'merchant_id' => $test->merchant->id,
                'name'        => '测试应用',
            ],
            [
                'merchant_id' => $test->merchant->id,
                'name'        => '测试应用',
                'status'      => MerchantAppStatusEnum::ENABLE->value
            ],
        );

        // 支付方式
        $test->paymentMethods[] = Method::firstOrCreate(
            [ 'code' => 'alipay' ],
            [ 'name' => '支付宝', 'code' => 'alipay' ]

        );
        $test->paymentMethods[] = Method::firstOrCreate(
            [ 'code' => 'wechat' ],
            [ 'name' => '微信', 'code' => 'wechat' ],

        );

        //  支付渠道

        $test->channels[] = Channel::firstOrCreate(
            [ 'code' => 'alipay' ],
            [ 'name' => '支付宝', 'code' => 'alipay' ]
        );

        $test->channels[] = Channel::firstOrCreate(
            [ 'code' => 'wechat' ],
            [ 'name' => '微信', 'code' => 'wechat' ]
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
        $test->channelProducts = [];
        // 设置渠道产品的 支持的支付模式
        foreach ($productsData as $productData) {
            $test->channelProducts[] = $channelProduct = ChannelProduct::firstOrCreate(
                [
                    'channel_code' => $productData['channel_code'],
                    'code'         => $productData['code'],
                ],
                $productData
            );

            foreach ($productData['modes'] as $mode) {
                ChannelProductMode::firstOrCreate([
                                                      'system_channel_product_id' => $channelProduct->id,
                                                      'method_code'                => $mode['method_code'],
                                                      'scene_code'                 => $mode['scene_code'],
                                                  ], [
                                                      'system_channel_product_id' => $channelProduct->id,
                                                      'method_code'                => $mode['method_code'],
                                                      'scene_code'                 => $mode['scene_code'],
                                                  ]);
            }
        }


        $channelAppsData   = [
            AlipayChannelAppData::channelApp()
        ];
        $test->channelApps = [];
        foreach ($channelAppsData as $channelAppData) {

            $channelMerchantData                          = [];
            $channelMerchantData['owner_type']            = 'user';
            $channelMerchantData['owner_id']              = 1;
            $channelMerchantData['type']                  = MerchantTypeEnum::GENERAL->value;
            $channelMerchantData['channel_code']          = $channelAppData['channel_code'];
            $channelMerchantData['channel_merchant_id']   = $channelAppData['channel_merchant_id'];
            $channelMerchantData['channel_merchant_name'] = $channelAppData['merchant_name'];


            $channelMerchant                              = ChannelMerchant::updateOrCreate(
                Arr::only($channelMerchantData, [
                    'owner_type',
                    'owner_id',
                    'channel_code',
                    'channel_merchant_id'
                ]),
                $channelMerchantData
            );
            $channelAppData['system_channel_merchant_id'] = $channelMerchant->id;
            $channelAppData['owner_type']                 = 'user';
            $channelAppData['owner_id']                   = 1;

            $test->channelApps[] = $channelApp = ChannelApp::updateOrCreate(
                Arr::only($channelAppData, [
                    'owner_type',
                    'owner_id',
                    'system_channel_merchant_id',
                    'channel_code',
                    'channel_app_id'
                ]),
                $channelAppData
            );
            // 设置应用签约的产品
            foreach ($test->channelProducts as $channelProduct) {
                if ($channelApp->channel_code === $channelProduct->channel_code) {
                    ChannelAppProduct::firstOrCreate([
                                                         'system_channel_product_id' => $channelProduct->id,
                                                         'system_channel_app_id'     => $channelApp->id,
                                                     ], [
                                                         'system_channel_product_id' => $channelProduct->id,
                                                         'system_channel_app_id'     => $channelApp->id,
                                                     ]);
                }
            }
        }


        //  给商户授权 渠道应用
        $test->merchantApp->channelApps()->sync(collect($test->channelApps)->pluck('id')->toArray());
    }


    public static function settleReceivers():array
    {
        return [
            ...AlipayChannelAppData::settleReceivers()
        ];
    }


}
