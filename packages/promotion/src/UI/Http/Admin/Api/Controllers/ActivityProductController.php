<?php

namespace RedJasmine\Promotion\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\ActivityProduct;
use RedJasmine\Promotion\UI\Http\Admin\Api\Resources\ActivityProductResource;

/**
 * 活动商品管理控制器
 */
class ActivityProductController extends Controller
{
    /**
     * 获取活动商品列表
     */
    public function index(Activity $activity, Request $request): JsonResponse
    {
        $this->authorize('view', $activity);

        $query = $activity->products()->with(['skus']);

        // 按商品状态筛选
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // 按商品名称搜索
        if ($request->has('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        $products = $query->paginate($request->get('per_page', 15));

        return $this->success([
            'data' => ActivityProductResource::collection($products->items()),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * 添加商品到活动
     */
    public function store(Activity $activity, Request $request): JsonResponse
    {
        $this->authorize('update', $activity);

        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer',
            'products.*.product_name' => 'required|string|max:255',
            'products.*.product_image' => 'nullable|string|max:500',
            'products.*.original_price' => 'required|numeric|min:0',
            'products.*.activity_price' => 'required|numeric|min:0',
            'products.*.activity_stock' => 'nullable|integer|min:0',
            'products.*.limit_quantity' => 'nullable|integer|min:1',
            'products.*.sort' => 'nullable|integer',
            'products.*.is_show' => 'boolean',
            'products.*.skus' => 'nullable|array',
            'products.*.skus.*.sku_id' => 'required|integer',
            'products.*.skus.*.sku_name' => 'required|string|max:255',
            'products.*.skus.*.original_price' => 'required|numeric|min:0',
            'products.*.skus.*.activity_price' => 'required|numeric|min:0',
            'products.*.skus.*.activity_stock' => 'nullable|integer|min:0',
            'products.*.skus.*.limit_quantity' => 'nullable|integer|min:1',
            'products.*.skus.*.is_show' => 'boolean',
        ]);

        $addedProducts = [];

        foreach ($validated['products'] as $productData) {
            // 检查商品是否已存在
            $existingProduct = $activity->products()
                ->where('product_id', $productData['product_id'])
                ->first();

            if ($existingProduct) {
                continue; // 跳过已存在的商品
            }

            // 创建活动商品
            $activityProduct = $activity->products()->create([
                'product_id' => $productData['product_id'],
                'product_name' => $productData['product_name'],
                'product_image' => $productData['product_image'] ?? null,
                'original_price' => $productData['original_price'],
                'activity_price' => $productData['activity_price'],
                'activity_stock' => $productData['activity_stock'] ?? null,
                'limit_quantity' => $productData['limit_quantity'] ?? null,
                'sort' => $productData['sort'] ?? 0,
                'is_show' => $productData['is_show'] ?? true,
                'status' => 'active',
            ]);

            // 添加SKU信息
            if (!empty($productData['skus'])) {
                foreach ($productData['skus'] as $skuData) {
                    $activityProduct->skus()->create([
                        'sku_id' => $skuData['sku_id'],
                        'sku_name' => $skuData['sku_name'],
                        'original_price' => $skuData['original_price'],
                        'activity_price' => $skuData['activity_price'],
                        'activity_stock' => $skuData['activity_stock'] ?? null,
                        'limit_quantity' => $skuData['limit_quantity'] ?? null,
                        'is_show' => $skuData['is_show'] ?? true,
                        'status' => 'active',
                    ]);
                }
            }

            $addedProducts[] = $activityProduct->load('skus');
        }

        // 更新活动商品总数
        $activity->total_products = $activity->products()->count();
        $activity->save();

        return $this->success(
            ActivityProductResource::collection($addedProducts),
            '商品添加成功'
        );
    }

    /**
     * 更新活动商品
     */
    public function update(Activity $activity, ActivityProduct $product, Request $request): JsonResponse
    {
        $this->authorize('update', $activity);

        if ($product->activity_id !== $activity->id) {
            return $this->error('商品不属于该活动');
        }

        $validated = $request->validate([
            'product_name' => 'sometimes|string|max:255',
            'product_image' => 'sometimes|nullable|string|max:500',
            'original_price' => 'sometimes|numeric|min:0',
            'activity_price' => 'sometimes|numeric|min:0',
            'activity_stock' => 'sometimes|nullable|integer|min:0',
            'limit_quantity' => 'sometimes|nullable|integer|min:1',
            'sort' => 'sometimes|integer',
            'is_show' => 'sometimes|boolean',
            'status' => 'sometimes|in:active,inactive,sold_out',
        ]);

        $product->update($validated);

        return $this->success(
            new ActivityProductResource($product->load('skus')),
            '商品更新成功'
        );
    }

    /**
     * 删除活动商品
     */
    public function destroy(Activity $activity, ActivityProduct $product): JsonResponse
    {
        $this->authorize('update', $activity);

        if ($product->activity_id !== $activity->id) {
            return $this->error('商品不属于该活动');
        }

        // 删除关联的SKU
        $product->skus()->delete();
        
        // 删除活动商品
        $product->delete();

        // 更新活动商品总数
        $activity->total_products = $activity->products()->count();
        $activity->save();

        return $this->success(null, '商品删除成功');
    }

    /**
     * 批量删除活动商品
     */
    public function batchDestroy(Activity $activity, Request $request): JsonResponse
    {
        $this->authorize('update', $activity);

        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:promotion_activity_products,id',
        ]);

        $products = $activity->products()
            ->whereIn('id', $validated['product_ids'])
            ->get();

        foreach ($products as $product) {
            // 删除关联的SKU
            $product->skus()->delete();
            // 删除活动商品
            $product->delete();
        }

        // 更新活动商品总数
        $activity->total_products = $activity->products()->count();
        $activity->save();

        return $this->success(null, '批量删除成功');
    }

    /**
     * 批量更新商品状态
     */
    public function batchUpdateStatus(Activity $activity, Request $request): JsonResponse
    {
        $this->authorize('update', $activity);

        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:promotion_activity_products,id',
            'status' => 'required|in:active,inactive,sold_out',
        ]);

        $activity->products()
            ->whereIn('id', $validated['product_ids'])
            ->update(['status' => $validated['status']]);

        return $this->success(null, '状态更新成功');
    }

    /**
     * 批量更新商品显示状态
     */
    public function batchUpdateShow(Activity $activity, Request $request): JsonResponse
    {
        $this->authorize('update', $activity);

        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:promotion_activity_products,id',
            'is_show' => 'required|boolean',
        ]);

        $activity->products()
            ->whereIn('id', $validated['product_ids'])
            ->update(['is_show' => $validated['is_show']]);

        return $this->success(null, '显示状态更新成功');
    }

    /**
     * 权限验证
     */
    public function authorize($ability, $arguments = []): bool
    {
        // 这里可以实现具体的权限验证逻辑
        return true;
    }
}
