<?php

namespace RedJasmine\Order\UI\Http\Admin\Api\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeReturnGoodsCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCancelCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundConfirmCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCreateCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundLogisticsReshipmentCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundRejectCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundReturnGoodsCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\UI\Http\Admin\Api\Resources\OrderRefundResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class RefundController extends Controller
{

    public function __construct(

        protected RefundApplicationService $service,
        protected OrderApplicationService $orderCommandService,
    ) {


    }

    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(PaginateQuery::from($request->query()));
        return OrderRefundResource::collection($result);
    }


    public function show(Request $request, int $id) : OrderRefundResource
    {
        $refund = $this->service->find(FindQuery::make($id, $request));

        return OrderRefundResource::make($refund);
    }

    public function store(Request $request) : JsonResponse
    {
        $command = RefundCreateCommand::from($request);

        $refundId = $this->service->create($command);

        return static::success(['id' => $refundId]);
    }


    public function reject(Request $request) : JsonResponse
    {
        $command = RefundRejectCommand::from($request);
        $this->service->find(FindQuery::make($request->id));
        $this->service->reject($command);

        return static::success();

    }

    public function cancel(Request $request) : JsonResponse
    {
        $command = RefundCancelCommand::from($request);
        $this->service->find(FindQuery::make($request->id));
        $this->service->cancel($command);

        return static::success();

    }

    public function refundGoods(Request $request) : JsonResponse
    {
        $command = RefundReturnGoodsCommand::from($request);

        $this->service->find(FindQuery::make($command->id));
        $this->service->returnGoods($command);
        return static::success();
    }


    public function agreeRefund(Request $request) : JsonResponse
    {
        $command = RefundAgreeRefundCommand::from($request->all());
        $this->service->find(FindQuery::make($command->id));

        $this->service->agreeRefund($command);

        return static::success();
    }

    public function agreeReturnGoods(Request $request) : JsonResponse
    {
        $command = RefundAgreeReturnGoodsCommand::from($request->all());
        $this->service->find(FindQuery::make($command->id));

        $this->service->agreeReturnGoods($command);

        return static::success();
    }


    public function confirm(Request $request) : JsonResponse
    {
        $command = RefundConfirmCommand::from($request->all());
        $this->service->find(FindQuery::make($command->id));

        $this->service->confirm($command);

        return static::success();
    }


    // TODO
    public function reshipment(Request $request) : JsonResponse
    {
        $command = RefundLogisticsReshipmentCommand::from($request->all());
        $this->service->find(FindQuery::make($command->id));

        $this->service->reshipment($command);

        return static::success();
    }


    public function destroy($id) : void
    {
        abort(405);
    }
}
