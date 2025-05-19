<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Controller;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderHiddenCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderRemarksCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\UI\Http\Buyer\Api\Resources\OrderResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class OrderController extends Controller
{
    public function __construct(

        protected OrderApplicationService $service,
    ) {

        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyBuyer($this->getOwner());
        });
    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(PaginateQuery::from($request->query()));

        return OrderResource::collection($result->appends($request->query()));
    }

    public function show(Request $request, int $id) : OrderResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));

        return OrderResource::make($result);
    }


    public function store(Request $request) : OrderResource
    {
        $request->offsetSet('buyer', $this->getOwner());

        $command = OrderCreateCommand::from($request->all());
        $result  = $this->service->create($command);

        return OrderResource::make($result);
    }

    public function paying(Request $request) : JsonResponse
    {
        $order = $this->service->find(FindQuery::from($request));

        $command = OrderPayingCommand::from(['id' => $order->id, 'amount' => $order->payable_amount]);
        $payment = $this->service->paying($command);

        return static::success(['id' => $order->id, 'order_payment' => $payment, 'amount' => $order->payable_amount->value()]);
    }


    public function confirm(Request $request) : JsonResponse
    {
        $order = $this->service->find(FindQuery::from($request));

        $command = OrderConfirmCommand::from($request->all());
        $this->service->confirm($command);

        return static::success();
    }

    public function cancel(Request $request) : JsonResponse
    {
        $command = OrderCancelCommand::from($request->all());
        $this->service->find(FindQuery::from($request));
        $this->service->cancel($command);

        return static::success();
    }


    public function destroy($id) : JsonResponse
    {
        $command = OrderHiddenCommand::from(['id' => $id]);
        $this->service->find(FindQuery::make($command->id));
        $this->service->buyerHidden($command);

        return static::success();
    }


    public function remarks(Request $request) : JsonResponse
    {
        $this->service->find(FindQuery::from($request));
        $command = OrderRemarksCommand::from($request->all());

        $this->service->buyerRemarks($command);
        return static::success();
    }
}
