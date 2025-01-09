<?php

namespace RedJasmine\Payment\UI\Http\Notify\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Services\AsyncNotify\Commands\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelHandlerService;
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

        Log::info('Payment-Notify-Request;channel:' . $channel, ['data'=>$request->all(),'url'=>$request->url()]);
        // 验证 回调 url 签名
        PaymentUrl::validSignature(compact('app', 'channel', 'time', 'signature'));
        $data = $request->all();

        // 调用渠道支付回调方法
        $command              = new ChannelNotifyTradeCommand();
        $command->channelCode = $channel;
        $command->appId       = $app;
        $command->content     = $data;
        return app(PaymentChannelHandlerService::class)->tradeNotify($command);

    }

}
