<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Controller;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\OrderQueryService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\UI\Http\Buyer\Api\Resources\OrderResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class OrderController extends Controller
{
    public function __construct(
        protected readonly OrderQueryService $queryService,
        protected OrderCommandService        $commandService,
    )
    {

        $this->queryService->withQuery(function ($query) {
            $query->onlyBuyer($this->getOwner());
        });
    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(PaginateQuery::from($request->query()));

        return OrderResource::collection($result->appends($request->query()));
    }

    public function show(Request $request, int $id) : OrderResource
    {
        $result = $this->queryService->find($id, FindQuery::from($request->all()));

        return OrderResource::make($result);
    }


    public function store(Request $request) : OrderResource
    {
        $request->offsetSet('buyer', $this->getOwner());

        $command = OrderCreateCommand::from($request->all());
        $result  = $this->commandService->create($command);

        return OrderResource::make($result);
    }

    public function paying(Request $request) : JsonResponse
    {
        $order = $this->queryService->find($request->id);

        $command = OrderPayingCommand::from([ 'id' => $order->id, 'amount' => $order->payable_amount ]);
        $payment = $this->commandService->paying($command);

        return static::success([ 'id' => $order->id, 'order_payment' => $payment, 'amount' => $order->payable_amount->value() ]);
    }


    public function confirm(Request $request) : JsonResponse
    {
        $order = $this->queryService->find($request->id);

        $command = OrderConfirmCommand::from($request->all());
        $this->commandService->confirm($command);

        return static::success();
    }

    public function cancel(Request $request) : JsonResponse
    {
        $command = OrderCancelCommand::from($request->all());
        $this->queryService->find($request->id);
        $this->commandService->cancel($command);

        return static::success();
    }


    public function destroy($id) : JsonResponse
    {
        $command = OrderHiddenCommand::from([ 'id' => $id ]);
        $this->queryService->find($command->id);
        $this->commandService->buyerHidden($command);

        return static::success();
    }


    public function remarks(Request $request) : JsonResponse
    {
        $this->queryService->find($request->id);
        $command = OrderRemarksCommand::from($request->all());

        $this->commandService->buyerRemarks($command);
        return static::success();
    }
}
