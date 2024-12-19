<?php

namespace RedJasmine\Payment\UI\Http\Notify\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Commands\Notify\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Application\Services\ChannelNotifyCommandService;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;

class NotifyController extends Controller
{


    /**
     * 接受回调
     * @param string $channel
     * @param int $app
     * @param string $time
     * @param string $signature
     * @param Request $request
     * @return mixed
     */
    public function notify(string $channel, int $app, string $time, string $signature, Request $request)
    {
        // 验证 回调 url 签名
        PaymentUrl::validSignature(compact('app', 'channel', 'time', 'signature'));
        $data = $request->all();
        Log::info('Payment-Notify;channel:' . $channel, $request->all());
        // 调用渠道支付回调方法
        $command              = new ChannelNotifyTradeCommand();
        $command->channelCode = $channel;
        $command->appId       = $app;
        $command->content     = $data;
        return app(ChannelNotifyCommandService::class)->tradeNotify($command);

    }

}
