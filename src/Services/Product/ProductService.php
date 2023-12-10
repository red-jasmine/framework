<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Enums\Product\ProductStatus;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Services\Product\Builder\ProductBuilder;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Traits\Services\HasQueryBuilder;
use RedJasmine\Support\Traits\WithUserService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class ProductService
{
    use WithUserService;


    public string $model = Product::class;
    /**
     * 最大库存
     */
    public const MAX_QUANTITY = 999999999;

    use HasQueryBuilder {
        query as __query;
    }

    protected ?ProductBuilder $productBuilder = null;

    public function productBuilder() : ProductBuilder
    {
        if ($this->productBuilder) {
            return $this->productBuilder;
        }

        $this->productBuilder = new ProductBuilder();
        $this->productBuilder
            ->setOwner($this->getOwner())
            ->setOperator($this->getOperator());
        return $this->productBuilder;
    }
    public function filters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_uid'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('shipping_type'),
            AllowedFilter::partial('title'),
            AllowedFilter::exact('outer_id'),
            AllowedFilter::exact('is_multiple_spec'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('brand_id'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('seller_category_id'),
            static::searchFilter([ 'title', 'keywords' ])
        ];
    }


    public function includes() : array
    {
        return [
            'info', 'skus', 'skus.info', 'brand', 'category', 'sellerCategory'
        ];
    }


    public function query() : QueryBuilder
    {
        $query = $this->__query();
        return $query->productable();
    }


    public function lists() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()->paginate();
    }

    /**
     * @param $id
     *
     * @return Product
     */
    public function find($id) : Product
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * 创建商品
     *
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function create(array $data) : Product
    {
        // 验证数据
        $builder            = $this->productBuilder();
        $data['owner_type'] = $this->getOwner()->getUserType();
        $data['owner_uid']  = $this->getOwner()->getUID();
        $data               = $builder->validate($data);
        return $this->save(null, $data);
    }


    /**
     * 商品更新
     *
     * @param int   $id
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function update(int $id, array $data) : Product
    {
        $product = $this->find($id);
        // 验证数据
        $builder            = $this->productBuilder();
        $data['owner_type'] = $product->owner_type;
        $data['owner_uid']  = $product->owner_uid;

        $data = $builder->validate($data);
        return $this->save($id, $data);
    }

    /**
     * 删除
     *
     * @param int   $id
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function modify(int $id, array $data) : Product
    {
        $product = $this->find($id);
        // 验证数据
        $builder            = $this->productBuilder();
        $data['owner_type'] = $product->owner_type;
        $data['owner_uid']  = $product->owner_uid;

        $data = $builder->validateOnly($data);

        return $this->save($id, $data);
    }


    /**
     * @param int|null $id
     * @param array    $data
     *
     * @return Product
     * @throws Throwable
     */
    protected function save(?int $id = null, array $data = []) : Product
    {
        $builder = $this->productBuilder();
        if (filled($id)) {
            $product     = $this->find($id);
            $productInfo = $product->info;
        } else {
            $product = new Product();

            $productInfo = new ProductInfo();
        }
        $product->id     = $product->id ?? $builder->generateID();
        $productInfo->id = $product->id;
        // 保存数据
        DB::beginTransaction();
        try {
            // 生成商品ID
            $info = $data['info'] ?? [];
            unset($data['info']);
            $skus = $data['skus'] ?? [];
            unset($data['skus']);
            foreach ($data as $key => $value) {
                $product->setAttribute($key, $value);
            }
            foreach ($info as $infoKey => $infoValue) {
                $productInfo->setAttribute($infoKey, $infoValue);
            }

            if (blank($product->owner_type)) {
                $product->withOwner($this->getOwner());
            }
            if (blank($product->creator_type)) {
                $product->withCreator($this->getOperator());
            }
            $product->withUpdater($this->getOperator());
            $this->linkageTime($product);
            $product->modified_time = now();
            $product->save();
            $product->info()->save($productInfo);
            // 对多规格操作
            if (filled($data['is_multiple_spec'] ?? null)) {

                // 获取数据库中所有的SKU
                /**
                 * @var array|Product[] $skuModelList
                 */
                $skuModelList = $product->skus()
                                        ->withTrashed()
                                        ->with([ 'info' => function ($query) {
                                            $query->withTrashed();
                                        } ])
                                        ->get()
                                        ->keyBy('properties');


                $skus = collect($skus)->map(function ($sku) use ($product, $builder, $skuModelList) {

                    $skuModel     = $skuModelList[$sku['properties']] ?? new ($this->model)();
                    $skuModel->id = $skuModel->id ?? $builder->generateID();
                    $this->copyProductAttributeToSku($product, $skuModel);
                    $this->linkageTime($product);

                    if (blank($skuModel->creator_type)) {
                        $skuModel->withCreator($this->getOperator());
                    }
                    $skuModel->withUpdater($this->getOperator());

                    foreach ($sku as $key => $value) {
                        $skuModel->setAttribute($key, $value);
                    }
                    return $skuModel;
                })->keyBy('properties');

                $keys = $skus->keys()->all();
                $product->skus()->saveMany($skus);
                collect($skus)->each(function ($sku) {
                    /**
                     * @var Product $sku
                     */
                    $productInfo             = $sku->info ?? new ProductInfo();
                    $productInfo->id         = $sku->id;
                    $productInfo->deleted_at = null;
                    $productInfo->save();

                });
                // 无效的SKU 进行关闭
                foreach ($skuModelList as $properties => $sku) {
                    if (!in_array($properties, $keys, true)) {
                        $sku->status     = ProductStatus::DELETED;
                        $sku->deleted_at = now();
                        $sku->withUpdater($this->getOperator());
                        $sku->info->deleted_at = now();
                        $sku->save();
                        $sku->info->save();
                    }
                }
            }

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $product;
    }


    /**
     * 联动设置时间
     *
     * @param Product $product
     *
     * @return void
     */
    protected function linkageTime(Product $product) : void
    {

        if (!$product->isDirty('status')) {
            return;
        }
        switch ($product->status) {
            case ProductStatus::IN_STOCK: // 仓库中
                $product->sold_out_time = now();
                break;
            case ProductStatus::ON_SALE: // 在售
                $product->on_sale_time = now();
                break;
            case ProductStatus::OUT_OF_STOCK: // 缺货
                $product->sold_out_time = now();
                break;
            case ProductStatus::SOLD_OUT: // 售停
                $product->sold_out_time = now();
                break;
            case ProductStatus::OFF_SHELF: // 下架
                $product->off_sale_time = now();
                break;
            case ProductStatus::PRE_SALE:

                break;
            case ProductStatus::FORCED_OFF_SHELF:// 强制下架
                $product->on_sale_time  = null;
                $product->sold_out_time = null;
                $product->off_sale_time = $product->off_sale_time ?? now();
                break;
            case ProductStatus::DELETED:

                break;

        }
    }

    /**
     * 复制商品的值
     *
     * @param Product $product
     * @param Product $sku
     *
     * @return void
     */
    protected function copyProductAttributeToSku(Product $product, Product $sku) : void
    {
        $sku->owner_type         = $product->owner_type;
        $sku->owner_uid          = $product->owner_uid;
        $sku->is_multiple_spec   = 0;
        $sku->is_sku             = 1;
        $sku->spu_id             = $product->id;
        $sku->title              = $product->title;
        $sku->product_type       = $product->product_type;
        $sku->shipping_type      = $product->shipping_type;
        $sku->title              = $product->title;
        $sku->category_id        = $product->category_id;
        $sku->seller_category_id = $product->seller_category_id;
        $sku->freight_payer      = $product->freight_payer;
        $sku->postage_id         = $product->postage_id;
        $sku->sub_stock          = $product->sub_stock;
        $sku->delivery_time      = $product->delivery_time;
        $sku->vip                = $product->vip ?? 0;
        $sku->points             = $product->points ?? 0;
        $sku->status             = $product->status;

        $sku->deleted_at = null;
    }

    /**
     * 删除
     *
     * @param int $id
     *
     * @return true
     * @throws AbstractException
     * @throws Throwable
     */
    public function delete(int $id) : true
    {
        try {
            DB::beginTransaction();
            $product = $this->find($id);
            $product->info->delete();
            foreach ($product->skus as $sku) {
                $sku->info->delete();
            }
            $product->skus()->delete();
            $product->delete();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;
    }


    /**
     *  强制删除
     *
     * @param int $id
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function forceDelete(int $id) : bool
    {
        try {
            DB::beginTransaction();
            /**
             * @var Product $product
             */
            $product = $this->query()->onlyTrashed()->find($id);
            $product->info()->onlyTrashed()->forceDelete();

            foreach ($product->skus as $sku) {
                $sku->info()->onlyTrashed()->forceDelete();
            }
            $product->skus()->onlyTrashed()->forceDelete();
            $product->forceDelete();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;


    }

    /**
     * 恢复
     *
     * @param int $id
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function restore(int $id) : bool
    {
        try {
            DB::beginTransaction();
            /**
             * @var Product $product
             */
            $product = $this->query()->onlyTrashed()->find($id);
            $product->info()->onlyTrashed()->restore();

            foreach ($product->skus()->withTrashed()->where('status', '<>', ProductStatus::DELETED->value)->get() as $sku) {
                $sku->info()->onlyTrashed()->restore();
            }
            $product->skus()->onlyTrashed()->where('status', '<>', ProductStatus::DELETED->value)->restore();
            $product->restore();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;


    }


    /**
     * 设状态
     *
     * @param int           $id
     * @param ProductStatus $productStatus
     *
     * @return bool
     */
    public function updateStatus(int $id, ProductStatus $productStatus) : bool
    {
        $product                = $this->find($id);
        $product->status        = $productStatus;
        $product->modified_time = now();
        $product->withUpdater($this->getOperator());
        $product->save();

        return true;
    }
}
