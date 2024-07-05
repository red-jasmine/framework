<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;


use JsonException;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryQueryService;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\Sku;
use RedJasmine\Product\Application\Property\Services\PropertyValidateService;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Domain\Product\PropertyFormatter;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Application\CommandHandler;
use Throwable;

/**
 * @method  ProductCommandService getService()
 */
class ProductCommand extends CommandHandler
{

    public function __construct(
        protected BrandQueryService                 $brandQueryService,
        protected StockCommandService               $stockCommandService,
        protected PropertyFormatter                 $propertyFormatter,
        protected PropertyValidateService           $propertyValidateService,
        protected ProductCategoryQueryService       $categoryQueryService,
        protected ProductSellerCategoryQueryService $sellerCategoryQueryService,
    )
    {

        parent::__construct();

    }


    protected function defaultSku(Product $product) : ProductSku
    {
        $sku                  = new ProductSku();
        $sku->id              = $product->id;
        $sku->properties      = '';
        $sku->properties_name = '';
        $sku->price           = $product->price;
        $sku->cost_price      = $product->cost_price;
        $sku->market_price    = $product->market_price;
        $sku->safety_stock    = $product->safety_stock;
        $sku->image           = $product->image;
        $sku->barcode         = $product->barcode;
        $sku->outer_id        = $product->outer_id;
        $sku->status          = ProductStatusEnum::ON_SALE;
        $sku->deleted_at      = null;
        return $sku;
    }

    protected function fillProduct(Product $product, \RedJasmine\Product\Application\Product\UserCases\Commands\Product $command) : void
    {
        $product->owner               = $command->owner;
        $product->supplier            = $command->supplier;
        $product->product_type        = $command->productType;
        $product->shipping_type       = $command->shippingType;
        $product->title               = $command->title;
        $product->image               = $command->image;
        $product->barcode             = $command->barcode;
        $product->outer_id            = $command->outerId;
        $product->is_multiple_spec    = $command->isMultipleSpec;
        $product->sort                = $command->sort;
        $product->unit                = $command->unit;
        $product->price               = $command->price;
        $product->market_price        = $command->marketPrice;
        $product->cost_price          = $command->costPrice;
        $product->brand_id            = $command->brandId;
        $product->category_id         = $command->categoryId;
        $product->seller_category_id  = $command->sellerCategoryId;
        $product->freight_payer       = $command->freightPayer;
        $product->postage_id          = $command->postageId;
        $product->min_limit           = $command->minLimit;
        $product->max_limit           = $command->maxLimit;
        $product->step_limit          = $command->stepLimit;
        $product->sub_stock           = $command->subStock;
        $product->delivery_time       = $command->deliveryTime;
        $product->vip                 = $command->vip;
        $product->points              = $command->points;
        $product->is_hot              = $command->isHot;
        $product->is_new              = $command->isNew;
        $product->is_best             = $command->isBest;
        $product->is_benefit          = $command->isBenefit;
        $product->promise_services    = $command->promiseServices;
        $product->safety_stock        = $command->safetyStock;
        $product->supplier_product_id = $command->supplierProductId;


        $product->info->id          = $product->id;
        $product->info->videos      = $command->videos;
        $product->info->images      = $command->images;
        $product->info->keywords    = $command->keywords;
        $product->info->description = $command->description;
        $product->info->detail      = $command->detail;
        $product->info->videos      = $command->videos;
        $product->info->weight      = $command->weight;
        $product->info->width       = $command->width;
        $product->info->height      = $command->height;
        $product->info->length      = $command->length;
        $product->info->size        = $command->size;
        $product->info->remarks     = $command->remarks;
        $product->info->tools       = $command->tools;
        $product->info->expands     = $command->expands;

        $product->info->basic_props = $this->propertyValidateService->basicProps($command->basicProps?->toArray() ?? []);


        $product->setStatus($command->status);
    }


    protected function fillSku(ProductSku $sku, Sku $data) : void
    {
        $sku->properties      = $data->properties;
        $sku->properties_name = $data->propertiesName;
        $sku->image           = $data->image;
        $sku->barcode         = $data->barcode;
        $sku->outer_id        = $data->outerId;
        $sku->properties      = $data->properties;
        $sku->price           = $data->price;
        $sku->safety_stock    = $data->safetyStock;
        $sku->market_price    = $data->marketPrice;
        $sku->cost_price      = $data->costPrice;
        $sku->supplier_sku_id = $data->supplierSkuId;
        $sku->status          = $data->status;
        $sku->deleted_at      = null;
    }


    /**
     * @param Product $product
     *
     * @return void
     * @throws ProductException
     */
    protected function validateBrand(Product $product) : void
    {
        try {
            if ($product->brand_id && !$this->brandQueryService->isAllowUse($product->brand_id)) {
                throw new ProductException('品牌不可使用');
            }
        } catch (\Throwable $exception) {
            throw new ProductException('品牌不可使用');
        }
    }


    /**
     * @param Product $product
     *
     * @return void
     * @throws ProductException
     */
    protected function validateCategory(Product $product) : void
    {

        try {
            if ($product->category_id && !$this->categoryQueryService->isAllowUse($product->category_id)) {
                throw new ProductException('类目不可使用');
            }
        } catch (\Throwable $exception) {
            throw new ProductException('类目不可使用');
        }
    }

    /**
     * @param Product $product
     *
     * @return void
     * @throws ProductException
     */
    protected function validateSellerCategory(Product $product) : void
    {


        try {
            if ($product->seller_category_id
                && !$this->sellerCategoryQueryService->isAllowUse($product->seller_category_id, $product->owner)) {
                throw new ProductException('卖家分类不可使用');
            }
        } catch (\Throwable $exception) {
            throw  $exception;
            throw new ProductException('卖家分类不可使用');
        }
    }

    /**
     * @param Product                                                            $product
     * @param \RedJasmine\Product\Application\Product\UserCases\Commands\Product $command
     *
     * @return void
     * @throws ProductPropertyException
     * @throws ProductException|JsonException
     */
    public function handleCore(Product $product, \RedJasmine\Product\Application\Product\UserCases\Commands\Product $command) : void
    {
        // 基础验证
        // 验证 销售属性和 规格一一对应
        $this->fillProduct($product, $command);

        $this->validateBrand($product);

        $this->validateCategory($product);

        $this->validateSellerCategory($product);

        // 多规格区别处理
        switch ($command->isMultipleSpec) {
            case true: // 多规格

                $saleProps                 = $this->propertyValidateService->saleProps($command->saleProps->toArray());
                $product->info->sale_props = $saleProps->toArray();
                // 验证规格

                // 加入默认规格
                $defaultSku = $product->skus->where('properties', '')->first() ?? $this->defaultSku($product);
                $defaultSku->setDeleted();
                $product->addSku($defaultSku);

                $this->propertyValidateService->validateSkus($saleProps, $command->skus);
                $command->skus?->each(function ($skuData) use ($product) {
                    $sku     = $product->skus->where('properties', $skuData->properties)->first() ?? new ProductSku();
                    $sku->id = $sku->id ?? $this->getService()->buildId();
                    $this->fillSku($sku, $skuData);
                    $product->addSku($sku);
                });


                break;
            case false: // 单规格

                $product->info->sale_props = [];
                $defaultSku                = $product->skus->where('properties', '')->first() ?? $this->defaultSku($product);
                $defaultSku->setOnSale();
                $product->addSku($defaultSku);
                break;
        }
    }

    /**
     * @param Product                                                            $product
     * @param \RedJasmine\Product\Application\Product\UserCases\Commands\Product $command
     *
     * @return void
     * @throws StockException
     * @throws ProductStockException
     * @throws Throwable
     */
    protected function handleStock(Product $product, \RedJasmine\Product\Application\Product\UserCases\Commands\Product $command) : void
    {


        $skuCommand = $command->skus?->keyBy('properties');

        foreach ($product->skus as $sku) {

            // 修改库存 把 删除的库存设置为 0
            if ($sku->deleted_at) {
                $stock = 0;
            } else {
                $stock = $skuCommand[$sku->properties]?->stock ?? $command->stock;
            }

            $stockCommand             = new StockCommand();
            $stockCommand->productId  = $sku->product_id;
            $stockCommand->skuId      = $sku->id;
            $stockCommand->stock      = $stock;
            $stockCommand->changeType = ProductStockChangeTypeEnum::SELLER;

            // 设置库存
            $this->stockCommandService->set($stockCommand);
        }
    }


}
