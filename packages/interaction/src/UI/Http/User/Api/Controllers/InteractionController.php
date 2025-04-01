<?php

namespace RedJasmine\Interaction\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Interaction\Application\Services\Commands\InteractionCreateCommand;
use RedJasmine\Interaction\Application\Services\InteractionApplicationService;

class InteractionController extends Controller
{

    public function __construct(
        protected InteractionApplicationService $service,
    ) {
    }


    public function interactive(Request $request)
    {

        $request->offsetSet('user', $this->getOwner());
        $command = InteractionCreateCommand::from($request);

        $this->service->create($command);

    }

    // 取消互动
    public function cancel()
    {

    }

}