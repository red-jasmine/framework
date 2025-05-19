<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCancelCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCreateCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundReturnGoodsCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\UI\Http\Buyer\Api\Resources\OrderRefundResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class RefundController extends Controller
{

    public function __construct(
        protected RefundApplicationService $service,
    ) {


        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyBuyer($this->getOwner());
        });


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


    public function cancel(Request $request) : JsonResponse
    {
        $command = RefundCancelCommand::from($request);
        $this->service->find(FindQuery::make($command->id));
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


    public function destroy($id) : void
    {
        abort(405);
    }
}
