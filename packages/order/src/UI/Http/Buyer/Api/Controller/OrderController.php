<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Controller;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderHiddenCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderRemarksCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Application\Services\Orders\Queries\FindQuery;
use RedJasmine\Order\UI\Http\Buyer\Api\Resources\OrderResource;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class OrderController extends Controller
{
    public function __construct(

        protected OrderApplicationService $service,
    ) {

        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyBuyer($this->getOwner());
        });
    }

    use RestQueryControllerActions;

    /**
     * Authorize a given action for the current user.
     *
     * @param  mixed  $ability
     * @param  mixed|array  $arguments
     *
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorize($ability, $arguments = [])
    {
        return  true;
    }


    public static string $findQueryClass = FindQuery::class;
    public static string $resourceClass  = OrderResource::class;


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
