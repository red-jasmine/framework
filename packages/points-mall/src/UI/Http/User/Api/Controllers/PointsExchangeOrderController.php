<?php

namespace RedJasmine\PointsMall\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderCreateCommand;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderPayCommand;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\PointsExchangeOrderApplicationService;
use RedJasmine\PointsMall\UI\Http\User\Api\Resources\PointsExchangeOrderResource;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class PointsExchangeOrderController extends Controller
{

    use RestQueryControllerActions;

    public function __construct(
        protected PointsExchangeOrderApplicationService $service,
    ) {
    }

    public function exchange(Request $request) : PointsExchangeOrderResource
    {
        $request->offsetSet('user', $this->getOwner());
        $command = PointsExchangeOrderCreateCommand::from($request);

        $order = $this->service->create($command);

        return new PointsExchangeOrderResource($order);


    }

    public function pay($id, Request $request) : JsonResponse
    {
        $request->offsetSet('user', $this->getOwner());

        $command = new  PointsExchangeOrderPayCommand;
        $command->setKey($id);

        $result = $this->service->pay($command);

        return static::success($result);
    }
}