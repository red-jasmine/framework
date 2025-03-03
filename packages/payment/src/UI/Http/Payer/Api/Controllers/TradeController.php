<?php

namespace RedJasmine\Payment\UI\Http\Payer\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeCommandService;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;

class TradeController extends Controller
{
    public function __construct(
        public TradeCommandService $tradeCommandService

    ) {
    }


    public function ready(Request $request)
    {
        $id            = $request->input('trade_no');
        $merchantAppId = $request->input('merchant_app_id');

        // 获取当前环境
        $tradeReadyCommand                = new TradeReadyCommand();
        $tradeReadyCommand->merchantAppId = $merchantAppId;
        $tradeReadyCommand->tradeNo       = $id;
        $tradeReadyCommand->method        = null;
        $tradeReadyCommand->scene         = $request->enum('scene', SceneEnum::class); // 场景
        $tradeReadyCommand->client        = null; // 客户端
        $tradeReadyCommand->device        = null;// 设备
        $tradeReadyCommand->sdk           = null;
        $paymentTradeResult               = $this->tradeCommandService->ready($tradeReadyCommand);

        return static::success($paymentTradeResult);
    }

}