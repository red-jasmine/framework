<?php

namespace RedJasmine\Payment\UI\Http\Notify\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Application\Commands\Notify\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Application\Services\ChannelNotifyCommandService;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;
use RedJasmine\Payment\Domain\Models\ChannelApp;

class NotifyController extends Controller
{


    public function notify(string $channel, int $app, Request $request)
    {


        $data = json_decode('{"gmt_create":"2024-12-18 18:03:22","charset":"UTF-8","gmt_payment":"2024-12-18 18:03:26","notify_time":"2024-12-18 18:03:27","subject":"测试支付","sign":"IFxIV5tgOJouBJhqiu8rd6qDV2IRIaBhdJPqFlRRdRNET9W9RaEbmOXMAQdzxlbAwxfY88HAEPIX3CHV82/uRt3IK4Teq1axMYrX5vg78QyNWL07pVb12mgZQ3ke55IqBprou1om6pw+zfVRp/pH9JWboVHtrCLuqdQ8AJv8oi4uV08OGTfGFakU4FIn/vbm90S185kPkiKW5Mz3r5wpFwyajeGvhuFN0jhaOgSVeecOqivSe2f66NSg1rQJPdcZd97rKdYhJdERppiyZNShIMdVesj2+ulpIu7dBxRnx2KcLVEXypULxgEtPOfhuo94WisGzSfym9yP/PnivjGI9Q==","buyer_id":"2088402197998627","invoice_amount":"0.01","version":"1.0","notify_id":"2024121801222180327098621430261037","fund_bill_list":"[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","out_trade_no":"512351247192169","total_amount":"0.01","trade_status":"TRADE_SUCCESS","trade_no":"2024121822001498621426006325","auth_app_id":"2021003187696362","receipt_amount":"0.01","point_amount":"0.00","buyer_pay_amount":"0.01","app_id":"2021003187696362","sign_type":"RSA2","seller_id":"2088541980451571"}', true);

        Log::info('Payment-Notify;channel:' . $channel, $request->all());
        // 获取渠道信息
        // 获取应用信息

        // 调用渠道支付回调方法
        $service = app(ChannelNotifyCommandService::class);

        $command              = new ChannelNotifyTradeCommand();
        $command->channelCode = $channel;
        $command->appId       = $app;
        $command->content     = $data;
        $service->tradeNotify($command);
        return 'ok';
    }

}
