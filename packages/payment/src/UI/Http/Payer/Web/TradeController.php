<?php

namespace RedJasmine\Payment\UI\Http\Payer\Web;

use Illuminate\Http\Request;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePayingCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeCommandService;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;
use RedJasmine\Payment\Domain\Models\Enums\PaymentTriggerTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\Device;

class TradeController extends Controller
{

    public function __construct(
        public TradeCommandService $tradeCommandService

    ) {
    }
    // TODO
    // 订单支付页
    // 支付返回页
    // 支付收单页
    // 商户收银台

    // 订单支付页面

    // 网页返回页面

    // 网页发起支付

    public function show($id, string $time, string $signature, Request $request)
    {

        // 查询当前订单数据
        PaymentUrl::validSignature(compact('id', 'time', 'signature'));
        // TODO

        $device = new Device;

        // 获取当前环境
        $tradeReadyCommand                = new TradeReadyCommand;
        $tradeReadyCommand->merchantAppId = 536390448088200;
        $tradeReadyCommand->tradeNo       = $id;
        $tradeReadyCommand->method        = null;
        $tradeReadyCommand->scene         = SceneEnum::WEB;
        $tradeReadyCommand->device        = null;
        $tradeReadyCommand->client        = null;
        $tradeReadyCommand->sdk           = null;


        $paymentTradeResult = $this->tradeCommandService->ready($tradeReadyCommand);

        return view('red-jasmine-payment::payment', ['trade' => $paymentTradeResult]);

    }


    public function pay(Request $request)
    {

        // TODO 验证参数
        $command = new TradePayingCommand;

        $command->merchantAppId = 536390448088200;
        $command->tradeNo       = $request->trade_no;
        $command->method        = $request->input('method');
        $command->scene         = SceneEnum::WEB;
        $command->device        = null;
        $command->client        = null;
        $command->sdk           = null;

        $paymentTradeResult = $this->tradeCommandService->paying($command);

        if($paymentTradeResult->paymentTrigger->type  === PaymentTriggerTypeEnum::REDIRECT){
            return redirect($paymentTradeResult->paymentTrigger->content);
        }
        // TODO 前端 JS 发起


    }


}
