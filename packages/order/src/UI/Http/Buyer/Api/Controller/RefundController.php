<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\Orders\OrderCommandService;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCancelCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCreateCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundReturnGoodsCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundCommandService;
use RedJasmine\Order\Application\Services\Refunds\RefundQueryService;
use RedJasmine\Order\UI\Http\Buyer\Api\Resources\OrderRefundResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class RefundController extends Controller
{

    public function __construct(
        protected readonly RefundQueryService $queryService,
        protected RefundCommandService        $commandService,
        protected OrderCommandService         $orderCommandService,
    )
    {


        $this->queryService->getRepository()->withQuery(function ($query) {
            $query->onlyBuyer($this->getOwner());
        });


    }

    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(PaginateQuery::from($request->query()));
        return OrderRefundResource::collection($result);
    }


    public function show(Request $request, int $id) : OrderRefundResource
    {
        $refund = $this->queryService->find(FindQuery::make($id,$request));

        return OrderRefundResource::make($refund);
    }

    public function store(Request $request) : JsonResponse
    {
        $command = RefundCreateCommand::from($request);

        $refundId = $this->commandService->create($command);

        return static::success([ 'id' => $refundId ]);
    }


    public function cancel(Request $request) : JsonResponse
    {
        $command = RefundCancelCommand::from($request);
        $this->queryService->find(FindQuery::make($command->id));
        $this->commandService->cancel($command);

        return static::success();

    }

    public function refundGoods(Request $request) : JsonResponse
    {
        $command = RefundReturnGoodsCommand::from($request);
        $this->queryService->find(FindQuery::make($command->id));
        $this->commandService->returnGoods($command);
        return static::success();
    }


    public function destroy($id) : void
    {
        abort(405);
    }
}
