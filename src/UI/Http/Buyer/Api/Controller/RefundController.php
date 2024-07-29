<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\Services\RefundQueryService;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundReturnGoodsCommand;
use RedJasmine\Order\UI\Http\Buyer\Api\Resources\OrderRefundResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class RefundController extends Controller
{

    public function __construct(
        protected readonly RefundQueryService $queryService,
        protected RefundCommandService        $commandService,
        protected OrderCommandService         $orderCommandService,
    )
    {


        $this->queryService->withQuery(function ($query) {
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
        $refund = $this->queryService->find($id, FindQuery::from($request->all()));

        return OrderRefundResource::make($refund);
    }

    public function store(Request $request) : JsonResponse
    {
        $command = RefundCreateCommand::from($request);

        $rid = $this->commandService->create($command);

        return static::success([ 'rid' => $rid ]);
    }


    public function cancel(Request $request) : JsonResponse
    {
        $command = RefundCancelCommand::from($request);
        $this->queryService->find($request->rid);
        $this->commandService->cancel($command);

        return static::success();

    }

    public function refundGoods(Request $request) : JsonResponse
    {
        $command = RefundReturnGoodsCommand::from($request);
        $this->queryService->find($command->rid);
        $this->commandService->returnGoods($command);
        return static::success();
    }


    public function destroy($id) : void
    {
        abort(405);
    }
}
