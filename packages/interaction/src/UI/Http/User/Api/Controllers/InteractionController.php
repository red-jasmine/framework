<?php

namespace RedJasmine\Interaction\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Interaction\Application\Services\Commands\InteractionRecordCreateCommand;
use RedJasmine\Interaction\Application\Services\InteractionApplicationService;

class InteractionController extends Controller
{

    public function __construct(
        protected InteractionApplicationService $service,
    ) {
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse|JsonResource
     */
    public function interactive(Request $request) : JsonResponse|JsonResource
    {

        $request->offsetSet('user', $this->getOwner());

        $command = InteractionRecordCreateCommand::from($request);

        $this->service->create($command);


        return static::success();

    }

    // 取消互动
    public function cancel(Request $request) : JsonResponse|JsonResource
    {
        $request->offsetSet('user', $this->getOwner());

        $command = InteractionRecordCreateCommand::from($request);

        $this->service->create($command);


        return static::success();
    }


    public function records(Request $request)
    {

    }

}