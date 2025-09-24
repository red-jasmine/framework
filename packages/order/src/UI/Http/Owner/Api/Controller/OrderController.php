<?php

namespace RedJasmine\Order\UI\Http\Owner\Api\Controller;


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
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\UI\Http\Owner\Api\Resources\OrderResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class OrderController extends Controller
{
    public function __construct(

        protected OrderApplicationService $service,
    ) {

        $this->service->repository->withQuery(function ($query) {
            $query->onlySeller($this->getOwner());
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
        $command = OrderCreateCommand::from($request->all());
        $result  = $this->service->create($command);
        return OrderResource::make($result);
    }


    public function paying(Request $request) : JsonResponse
    {

        $command = OrderPayingCommand::from($request->all());

        $order = $this->service->find(FindQuery::make($command->id));

        $payment = $this->service->paying($command);

        return static::success([
            'id'     => $order->id, 'order_payment' => $payment,
            'amount' => $order->payable_amount->value()
        ]);
    }

    public function paid(Request $request) : JsonResponse
    {
        $command = OrderPaidCommand::from($request->all());

        $this->service->findByNo($command->orderNo);

        $this->service->paid($command);

        return static::success();
    }


    public function logisticsShipping(Request $request) : JsonResponse
    {

        $command = OrderLogisticsShippingCommand::from($request->all());

        $this->service->find(FindQuery::make($command->id));

        $this->service->logisticsShipping($command);

        return static::success();
    }

    public function dummyShipping(Request $request) : JsonResponse
    {

        $command = OrderDummyShippingCommand::from($request->all());

        $this->service->find(FindQuery::make($command->id));

        $this->service->dummyShipping($command);

        return static::success();
    }

    public function cardKeyShipping(Request $request) : JsonResponse
    {

        $command = OrderCardKeyShippingCommand::from($request->all());

        $this->service->find(FindQuery::make($command->id));

        $this->service->cardKeyShipping($command);

        return static::success();
    }


    public function destroy($id) : JsonResponse
    {

        $command = OrderHiddenCommand::from(['id' => $id]);
        $this->service->find(FindQuery::make($command->id));

        $this->service->sellerHidden($command);

        return static::success();
    }

    public function cancel(Request $request) : JsonResponse
    {

        $command = OrderCancelCommand::from($request->all());
        $this->service->find(FindQuery::make($command->id));
        $this->service->cancel($command);

        return static::success();
    }


    public function remarks(Request $request) : JsonResponse
    {
        $command = OrderRemarksCommand::from($request->all());

        $this->service->find(FindQuery::make($command->id));

        $this->service->sellerRemarks($command);
        return static::success();
    }


    public function progress(Request $request) : JsonResponse
    {
        $command = OrderProgressCommand::from($request->all());
        $this->service->find(FindQuery::make($command->id));
        $this->service->progress($command);
        return static::success();

    }
}
