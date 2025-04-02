<?php

namespace RedJasmine\Interaction\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Interaction\Application\Services\Commands\InteractionCreateCommand;
use RedJasmine\Interaction\Application\Services\InteractionApplicationService;

class InteractionController extends Controller
{

    public function __construct(
        protected InteractionApplicationService $service,
    ) {
    }


    public function interactive(Request $request) : JsonResponse|JsonResource
    {

        $request->offsetSet('user', $this->getOwner());
        $command = InteractionCreateCommand::from($request);

        $result = $this->service->create($command);


        return static::success(['record' => $result->id]);

    }

    // 取消互动
    public function cancel()
    {

    }

}