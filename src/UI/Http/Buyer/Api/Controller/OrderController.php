<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Controller;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\OrderQueryService;
use RedJasmine\Order\Application\Services\OrderService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Queries\OrderAllQuery;
use RedJasmine\Order\UI\Http\Buyer\Api\Resources\OrderResource;

class OrderController extends Controller
{
    public function __construct(protected readonly OrderQueryService $queryService,
                                protected OrderService               $orderService,
    )
    {
        $this->orderService->setOperator(function () {
            return $this->getUser();
        });

        $this->queryService->withQuery(function ($query) {
            $query->onlyBuyer($this->getOwner());
        });
    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(OrderAllQuery::from([ 'query' => $request->query() ]));
        return OrderResource::collection($result->appends($request->query()));
    }

    public function store(Request $request) : OrderResource
    {
        $request->offsetSet('buyer', $this->getOwner());
        $command = OrderCreateCommand::from($request->all());
        $result  = $this->orderService->create($command);
        return OrderResource::make($result);
    }

    public function paying(Request $request) : JsonResponse
    {
        $order = $this->queryService->find($request->id);

        $command   = OrderPayingCommand::from([ 'id' => $order->id, 'amount' => $order->payable_amount ]);
        $paymentId = $this->orderService->paying($command);

        return self::success([ 'id' => $order->id, 'order_payment_id' => $paymentId, 'amount' => $order->payable_amount ]);
    }


    public function confirm(Request $request)
    {

    }

    public function cancel(Request $request) : JsonResponse
    {
        $this->queryService->find($request->id);
        $this->orderService->cancel(OrderCancelCommand::from($request->all()));

        return static::success();
    }

    public function show(Request $request, int $id) : OrderResource
    {
        $result = $this->queryService->find($id, $request->query());
        return OrderResource::make($result);
    }

    public function destroy($id) : JsonResponse
    {
        $this->queryService->find($id);
        $command = OrderHiddenCommand::from([ 'id' => $id ]);
        $this->orderService->buyerHidden($command);
        return static::success();
    }
}
