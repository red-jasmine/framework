<?php

namespace RedJasmine\PointsMall\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderCreateCommand;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\PointsExchangeOrderApplicationService;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class PointsExchangeOrderController extends Controller
{

    use RestQueryControllerActions;

    public function __construct(
        protected PointsExchangeOrderApplicationService $service,
    ) {
    }

    public function exchange(Request $request)
    {
        $request->offsetSet('user', $this->getOwner());
        $command = PointsExchangeOrderCreateCommand::from($request);


        $this->service->create($command);

        dd($command);

    }
}