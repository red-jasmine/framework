<?php

namespace RedJasmine\PointsMall\UI\Http\Admin\Api\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Commands\PointsProductCreateCommand;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Commands\PointsProductDeleteCommand;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Commands\PointsProductOffSaleCommand;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Commands\PointsProductPublishCommand;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Commands\PointsProductUpdateCommand;
use RedJasmine\PointsMall\Application\Services\PointsProduct\PointsProductApplicationService;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Queries\PointsProductPaginationQuery;
use RedJasmine\PointsMall\Domain\Data\PointsProductData;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\UI\Http\Admin\Api\Resources\PointsProductResource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class PointsProductController extends Controller
{
    use RestControllerActions;


    protected static string $resourceClass      = PointsProductResource::class;
    protected static string $paginateQueryClass = PointsProductPaginationQuery::class;
    protected static string $modelClass         = PointsProduct::class;
    protected static string $dataClass          = PointsProductData::class;

    public function __construct(
        protected PointsProductApplicationService $service,
    ) {
        // 设置查询作用域
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        // 权限验证逻辑
        return true;
    }

    /**
     * 发布积分商品
     */
    public function publish(Request $request, int $id) : JsonResponse
    {
        $command = new PointsProductPublishCommand(
            id: $id,
            operator: $this->getOwner()
        );

        $product = $this->service->publish($command);

        return response()->json([
            'message' => '积分商品发布成功',
            'data'    => new PointsProductResource($product)
        ]);
    }

    /**
     * 下架积分商品
     */
    public function offSale(Request $request, int $id) : JsonResponse
    {
        $command = new PointsProductOffSaleCommand(
            id: $id,
            operator: $this->getOwner()
        );

        $product = $this->service->offSale($command);

        return response()->json([
            'message' => '积分商品下架成功',
            'data'    => new PointsProductResource($product)
        ]);
    }

    /**
     * 批量发布
     */
    public function batchPublish(Request $request) : JsonResponse
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', 'exists:points_products,id']
        ]);

        $ids     = $request->input('ids');
        $results = [];

        foreach ($ids as $id) {
            try {
                $command   = new PointsProductPublishCommand(
                    id: $id,
                    operator: $this->getOwner()
                );
                $product   = $this->service->publish($command);
                $results[] = [
                    'id'      => $id,
                    'success' => true,
                    'message' => '发布成功'
                ];
            } catch (Exception $e) {
                $results[] = [
                    'id'      => $id,
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'message' => '批量操作完成',
            'data'    => $results
        ]);
    }

    /**
     * 批量下架
     */
    public function batchOffSale(Request $request) : JsonResponse
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', 'exists:points_products,id']
        ]);

        $ids     = $request->input('ids');
        $results = [];

        foreach ($ids as $id) {
            try {
                $command   = new PointsProductOffSaleCommand(
                    id: $id,
                    operator: $this->getOwner()
                );
                $product   = $this->service->offSale($command);
                $results[] = [
                    'id'      => $id,
                    'success' => true,
                    'message' => '下架成功'
                ];
            } catch (Exception $e) {
                $results[] = [
                    'id'      => $id,
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'message' => '批量操作完成',
            'data'    => $results
        ]);
    }

    /**
     * 获取积分商品统计信息
     */
    public function statistics(Request $request) : JsonResponse
    {
        $query       = new PointsProductPaginationQuery();
        $allProducts = $this->service->repository->paginate($query);

        $statistics = [
            'total'    => $allProducts->total(),
            'on_sale'  => $allProducts->where('status', 'on_sale')->count(),
            'off_sale' => $allProducts->where('status', 'off_sale')->count(),
            'sold_out' => $allProducts->where('status', 'sold_out')->count(),
            'draft'    => $allProducts->where('status', 'draft')->count(),
        ];

        return response()->json([
            'data' => $statistics
        ]);
    }
} 