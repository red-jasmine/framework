<?php

namespace RedJasmine\Payment\Domain\Services;

use Overtrue\Socialite\SocialiteManager;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;

class PayerService
{

    public function login(ChannelApp $channelApp, string $code) : Payer
    {

        $config = [
            'app' => [
                'provider'      => 'wechat_mini', // TODO 根据 渠道 和环境判断
                'client_id'     => $channelApp->channel_app_id,
                'client_secret' => $channelApp->channel_app_secret,
            ],
        ];

        $socialite = new SocialiteManager($config);
        $provider  = $socialite->create('app');

        $user = $provider->userFromCode($code);

        $payer         = new Payer();
        $payer->type   = $channelApp->channel_code;
        $payer->appId  = $channelApp->channel_app_id;
        $payer->openId = $user->getId();

        return $payer;

    }


    protected function getSocialiteProvider(ChannelApp $channelApp)
    {

    }
}