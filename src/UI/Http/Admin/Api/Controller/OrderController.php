<?php

namespace RedJasmine\Order\UI\Http\Admin\Api\Controller;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\OrderQueryService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingCardKeyCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingLogisticsCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingVirtualCommand;
use RedJasmine\Order\UI\Http\Admin\Api\Resources\OrderResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class OrderController extends Controller
{
    public function __construct(
        protected readonly OrderQueryService $queryService,
        protected OrderCommandService        $commandService,
    )
    {

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
        $command = OrderCreateCommand::from($request->all());
        $result  = $this->commandService->create($command);
        return OrderResource::make($result);
    }


    public function paying(Request $request) : JsonResponse
    {

        $command = OrderPayingCommand::from($request->all());

        $order = $this->queryService->find($request->id);

        $payment = $this->commandService->paying($command);

        return static::success([ 'id' => $order->id, 'order_payment' => $payment, 'amount' => $order->payable_amount->value() ]);
    }

    public function paid(Request $request) : JsonResponse
    {
        $command = OrderPaidCommand::from($request->all());

        $this->queryService->find($command->id);

        $this->commandService->paid($command);

        return static::success();
    }


    public function shippingLogistics(Request $request) : JsonResponse
    {

        $command = OrderShippingLogisticsCommand::from($request->all());

        $this->queryService->find($command->id);

        $this->commandService->shippingLogistics($command);

        return static::success();
    }

    public function shippingVirtual(Request $request) : JsonResponse
    {

        $command = OrderShippingVirtualCommand::from($request->all());

        $this->queryService->find($command->id);

        $this->commandService->shippingVirtual($command);

        return static::success();
    }

    public function shippingCardKey(Request $request) : JsonResponse
    {

        $command = OrderShippingCardKeyCommand::from($request->all());

        $this->queryService->find($command->id);

        $this->commandService->shippingCardKey($command);

        return static::success();
    }


    public function destroy($id) : JsonResponse
    {

        $command = OrderHiddenCommand::from([ 'id' => $id ]);
        $this->queryService->find($command->id);

        $this->commandService->sellerHidden($command);

        return static::success();
    }

    public function cancel(Request $request) : JsonResponse
    {

        $command = OrderCancelCommand::from($request->all());
        $this->queryService->find($command->id);
        $this->commandService->cancel($command);

        return static::success();
    }


    public function remarks(Request $request) : JsonResponse
    {
        $this->queryService->find($request->id);
        $command = OrderRemarksCommand::from($request->all());

        $this->commandService->sellerRemarks($command);
        return static::success();
    }


    public function progress(Request $request) : JsonResponse
    {
        $command = OrderProgressCommand::from($request->all());
        $this->queryService->find($command->id);
        $this->commandService->progress($command);
        return static::success();

    }
}
