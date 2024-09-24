<?php

namespace RedJasmine\Product\Domain\Product\Transformer;

use JsonException;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Product\Application\Property\Services\PropertyValidateService;
use RedJasmine\Product\Domain\Product\Data\Product as Command;
use RedJasmine\Product\Domain\Product\Data\Sku;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Exceptions\ProductPropertyException;

class ProductTransformer
{

    public function __construct(
        protected PropertyValidateService $propertyValidateService,

    ) {
    }


    /**
     * @param  Product  $product
     * @param  Command  $command
     *
     * @return Product
     * @throws JsonException
     * @throws ProductPropertyException
     */
    public function transform(Product $product, Command $command) : Product
    {

        $this->fillProduct($product, $command);

        $this->handleMultipleSpec($product, $command);

        return $product;

    }

    /**
     * @param  Product  $product
     * @param  Command  $command
     *
     * @return void
     * @throws ProductPropertyException
     */
    protected function fillProduct(Product $product, Command $command) : void
    {
        $product->owner               = $command->owner;
        $product->supplier            = $command->supplier;
        $product->product_type        = $command->productType;
        $product->shipping_type       = $command->shippingType;
        $product->title               = $command->title;
        $product->slogan              = $command->slogan;
        $product->image               = $command->image;
        $product->barcode             = $command->barcode;
        $product->outer_id            = $command->outerId;
        $product->is_customized       = $command->isCustomized;
        $product->is_multiple_spec    = $command->isMultipleSpec;
        $product->sort                = $command->sort;
        $product->unit                = $command->unit;
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
        $product->supplier_product_id = $command->supplierProductId;


        $product->info->id               = $product->id;
        $product->info->promise_services = $command->promiseServices;
        $product->info->videos           = $command->videos;
        $product->info->images           = $command->images;
        $product->info->keywords         = $command->keywords;
        $product->info->description      = $command->description;
        $product->info->tips             = $command->tips;
        $product->info->detail           = $command->detail;
        $product->info->weight           = $command->weight;
        $product->info->width            = $command->width;
        $product->info->height           = $command->height;
        $product->info->length           = $command->length;
        $product->info->size             = $command->size;
        $product->info->remarks          = $command->remarks;
        $product->info->tools            = $command->tools;
        $product->info->expands          = $command->expands;

        $product->info->basic_props = $this->propertyValidateService->basicProps($command->basicProps?->toArray() ?? []);


        $product->setStatus($command->status);
    }


    /**
     * @param  Product  $product
     * @param  Command  $command
     *
     * @return void
     * @throws JsonException
     * @throws ProductPropertyException
     */
    protected function handleMultipleSpec(Product $product, Command $command) : void
    {
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
                    $sku = $product->skus->where('properties', $skuData->properties)->first() ?? new ProductSku();
                    if (!$sku?->id) {
                        $sku->setUniqueIds();
                    }
                    $this->fillSku($sku, $skuData);
                    $product->addSku($sku);
                });


                $product->price        = $product->skus->where('properties', '<>', '')->min('price');
                $product->market_price = $product->skus->where('properties', '<>', '')->min('market_price');
                $product->cost_price   = $product->skus->where('properties', '<>', '')->min('cost_price');
                $product->safety_stock = $product->skus->where('properties', '<>', '')->sum('safety_stock');

                break;
            case false: // 单规格

                $product->price        = $command->price;
                $product->market_price = $command->marketPrice;
                $product->cost_price   = $command->costPrice;
                $product->safety_stock = $command->safetyStock;


                $product->info->sale_props = [];
                $defaultSku                = $product->skus->where('properties', '')->first() ?? $this->defaultSku($product);
                $defaultSku->setOnSale();
                $product->addSku($defaultSku);
                break;
        }
    }

    protected function defaultSku(Product $product) : ProductSku
    {
        $sku                  = new ProductSku();
        $sku->id              = $product->id;
        $sku->properties      = '';
        $sku->properties_name = '';
        $sku->image           = $product->image;
        $sku->barcode         = $product->barcode;
        $sku->outer_id        = $product->outer_id;
        $sku->status          = ProductStatusEnum::ON_SALE;
        $sku->deleted_at      = null;
        $sku->price           = $product->price ?? 0;
        $sku->cost_price      = $product->cost_price ?? 0;
        $sku->market_price    = $product->market_price ?? 0;
        $sku->safety_stock    = $product->safety_stock ?? 0;
        return $sku;
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
}
