<?php

namespace RedJasmine\Product\Domain\Product\Transformer;

use JsonException;
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

    )
    {
    }


    /**
     * @param Product $product
     * @param Command $command
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
     * @param Product $product
     * @param Command $command
     *
     * @return void
     * @throws ProductPropertyException
     */
    protected function fillProduct(Product $product, Command $command) : void
    {
        $product->owner                     = $command->owner;
        $product->supplier                  = $command->supplier;
        $product->product_type              = $command->productType;
        $product->shipping_type             = $command->shippingType;
        $product->is_alone_order            = $command->isAloneOrder;
        $product->is_pre_sale               = $command->isPreSale;
        $product->title                     = $command->title;
        $product->slogan                    = $command->slogan;
        $product->image                     = $command->image;
        $product->barcode                   = $command->barcode;
        $product->outer_id                  = $command->outerId;
        $product->is_customized             = $command->isCustomized;
        $product->is_multiple_spec          = $command->isMultipleSpec;
        $product->sort                      = $command->sort;
        $product->unit                      = $command->unit;
        $product->unit_quantity             = $command->unitQuantity;
        $product->spu_id                    = $command->spuId;
        $product->category_id               = $command->categoryId;
        $product->brand_id                  = $command->brandId;
        $product->product_model             = $command->productModel;
        $product->product_group_id          = $command->productGroupId;
        $product->freight_payer             = $command->freightPayer;
        $product->postage_id                = $command->postageId;
        $product->min_limit                 = $command->minLimit;
        $product->max_limit                 = $command->maxLimit;
        $product->step_limit                = $command->stepLimit;
        $product->sub_stock                 = $command->subStock;
        $product->delivery_time             = $command->deliveryTime;
        $product->order_quantity_limit_type = $command->orderQuantityLimitType;
        $product->order_quantity_limit_num  = $command->orderQuantityLimitNum;
        $product->vip                       = $command->vip;
        $product->points                    = $command->points;
        $product->is_hot                    = $command->isHot;
        $product->is_new                    = $command->isNew;
        $product->is_best                   = $command->isBest;
        $product->is_benefit                = $command->isBenefit;
        $product->supplier_product_id       = $command->supplierProductId;
        $product->start_sale_time           = $command->startSaleTime;
        $product->end_sale_time             = $command->endSaleTime;
        $product->info->id                  = $product->id;
        $product->info->promise_services    = $command->promiseServices;
        $product->info->videos              = $command->videos;
        $product->info->images              = $command->images;
        $product->info->keywords            = $command->keywords;
        $product->info->description         = $command->description;
        $product->info->tips                = $command->tips;
        $product->info->detail              = $command->detail;
        $product->info->remarks             = $command->remarks;
        $product->info->tools               = $command->tools;
        $product->info->expands             = $command->expands;
        $product->info->form                = $command->form;
        $product->info->basic_props         = $this->propertyValidateService->basicProps($command->basicProps?->toArray() ?? []);
        $product->info->customize_props     = $command->customizeProps?->toArray() ?? [];


        $product->setRelation('extendProductGroups', collect($command->extendProductGroups));

        $product->setRelation('tags', collect($command->tags));

        $product->setStatus($command->status);
    }


    /**
     * @param Product $product
     * @param Command $command
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


                $this->propertyValidateService->validateSkus($saleProps, $command->skus);
                $command->skus?->each(function (Sku $skuData) use ($product) {
                    $sku = $product->skus
                               ->where('properties_sequence', $skuData->propertiesSequence)
                               ->first() ?? new ProductSku();
                    if (!$sku?->id) {
                        $sku->setUniqueIds();
                    }
                    $this->fillSku($sku, $skuData);
                    $product->addSku($sku);
                });


                // 统计项

                $product->price        = $product->skus->where('properties_sequence', '<>', '')->min('price');
                $product->market_price = $product->skus->where('properties_sequence', '<>', '')->min('market_price');
                $product->cost_price   = $product->skus->where('properties_sequence', '<>', '')->min('cost_price');
                $product->safety_stock = $product->skus->where('properties_sequence', '<>', '')->sum('safety_stock');


                // 加入默认规格
                $defaultSku = $product->skus->where('properties_sequence', '')->first() ?? $this->defaultSku($product, $command);
                $defaultSku->setDeleted();
                $product->addSku($defaultSku);

                break;
            case false: // 单规格
                $product->price            = $command->price;
                $product->cost_price       = $command->costPrice;
                $product->market_price     = $command->marketPrice;
                $product->safety_stock     = $command->safetyStock;
                $product->info->sale_props = [];
                $defaultSku                = $product->skus->where('properties_sequence', '')->first() ?? $this->defaultSku($product, $command);
                $defaultSku->setOnSale();
                $product->addSku($defaultSku);
                break;
        }
    }

    protected function fillSku(ProductSku $sku, Sku $skuData) : void
    {
        $sku->properties_sequence = $skuData->propertiesSequence;
        $sku->properties_name     = $skuData->propertiesName;
        $sku->image               = $skuData->image;
        $sku->barcode             = $skuData->barcode;
        $sku->outer_id            = $skuData->outerId;
        $sku->price               = $skuData->price;
        $sku->safety_stock        = $skuData->safetyStock;
        $sku->market_price        = $skuData->marketPrice;
        $sku->cost_price          = $skuData->costPrice;
        $sku->supplier_sku_id     = $skuData->supplierSkuId;
        $sku->weight              = $skuData->weight;
        $sku->width               = $skuData->width;
        $sku->height              = $skuData->height;
        $sku->length              = $skuData->length;
        $sku->size                = $skuData->size;
        $sku->status              = $skuData->status;
        $sku->deleted_at          = null;
    }

    protected function defaultSku(Product $product, Command $command) : ProductSku
    {

        $sku                      = new ProductSku();
        $sku->id                  = $product->id;
        $sku->properties_sequence = '';
        $sku->properties_name     = '';
        $sku->image               = $product->image;
        $sku->barcode             = $product->barcode;
        $sku->outer_id            = $product->outer_id;
        $sku->price               = $product->price ?? 0;
        $sku->cost_price          = $product->cost_price ?? null;
        $sku->market_price        = $product->market_price ?? null;
        $sku->safety_stock        = $product->safety_stock ?? 0;
        $sku->image               = $product->image;
        $sku->barcode             = $product->barcode;
        $sku->outer_id            = $product->outer_id;
        $sku->supplier_sku_id     = null;
        $sku->weight              = $command->weight;
        $sku->width               = $command->width;
        $sku->height              = $command->height;
        $sku->length              = $command->length;
        $sku->size                = $command->size;


        $sku->status     = ProductStatusEnum::ON_SALE;
        $sku->deleted_at = null;
        return $sku;
    }
}
