<?php

namespace RedJasmine\Payment\UI\Http\Payer\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePayingCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeApplicationService;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;

class TradeController extends Controller
{
    public function __construct(
        public TradeApplicationService $tradeCommandService

    ) {
    }


    public function ready(Request $request)
    {

        // 获取当前环境
        $command = TradeReadyCommand::from($request);

        $result = $this->tradeCommandService->ready($command);

        return static::success($result);
    }


    public function paying(Request $request)
    {

        $command = TradePayingCommand::from($request);

        $result = $this->tradeCommandService->paying($command);

        return static::success($result);

    }

}