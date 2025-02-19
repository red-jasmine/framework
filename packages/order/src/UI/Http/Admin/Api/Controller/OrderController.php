<?php

namespace RedJasmine\Order\UI\Http\Admin\Api\Controller;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCardKeyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderDummyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderHiddenCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderLogisticsShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderRemarksCommand;
use RedJasmine\Order\Application\Services\Orders\OrderCommandService;
use RedJasmine\Order\Application\Services\Orders\OrderQueryService;
use RedJasmine\Order\UI\Http\Admin\Api\Resources\OrderResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

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
        $result = $this->queryService->findById(FindQuery::make($id,$request));

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

        $order = $this->queryService->findById(FindQuery::make($command->id));

        $payment = $this->commandService->paying($command);

        return static::success([ 'id' => $order->id, 'order_payment' => $payment, 'amount' => $order->payable_amount->value() ]);
    }

    public function paid(Request $request) : JsonResponse
    {
        $command = OrderPaidCommand::from($request->all());

        $this->queryService->findById(FindQuery::make($command->id));

        $this->commandService->paid($command);

        return static::success();
    }


    public function logisticsShipping(Request $request) : JsonResponse
    {

        $command = OrderLogisticsShippingCommand::from($request->all());

        $this->queryService->findById(FindQuery::make($command->id));

        $this->commandService->logisticsShipping($command);

        return static::success();
    }

    public function dummyShipping(Request $request) : JsonResponse
    {

        $command = OrderDummyShippingCommand::from($request->all());

        $this->queryService->findById(FindQuery::make($command->id));

        $this->commandService->dummyShipping($command);

        return static::success();
    }

    public function cardKeyShipping(Request $request) : JsonResponse
    {

        $command = OrderCardKeyShippingCommand::from($request->all());

        $this->queryService->findById(FindQuery::make($command->id));

        $this->commandService->cardKeyShipping($command);

        return static::success();
    }


    public function destroy($id) : JsonResponse
    {

        $command = OrderHiddenCommand::from([ 'id' => $id ]);
        $this->queryService->findById(FindQuery::make($command->id));

        $this->commandService->sellerHidden($command);

        return static::success();
    }

    public function cancel(Request $request) : JsonResponse
    {

        $command = OrderCancelCommand::from($request->all());
        $this->queryService->findById(FindQuery::make($command->id));
        $this->commandService->cancel($command);

        return static::success();
    }


    public function remarks(Request $request) : JsonResponse
    {
        $command = OrderRemarksCommand::from($request->all());

        $this->queryService->findById(FindQuery::make($command->id));


        $this->commandService->sellerRemarks($command);
        return static::success();
    }


    public function progress(Request $request) : JsonResponse
    {
        $command = OrderProgressCommand::from($request->all());
        $this->queryService->findById(FindQuery::make($command->id));
        $this->commandService->progress($command);
        return static::success();

    }
}
