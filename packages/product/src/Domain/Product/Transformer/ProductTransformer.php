<?php

namespace RedJasmine\Product\Domain\Product\Transformer;

use JsonException;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValidateService;
use RedJasmine\Product\Domain\Product\Data\Product as Command;
use RedJasmine\Product\Domain\Product\Data\Sku;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Exceptions\ProductAttributeException;

class ProductTransformer
{

    public function __construct(
        protected ProductAttributeValidateService $attributeValidateService,

    ) {
    }


    /**
     * @param  Product  $product
     * @param  Command  $command
     *
     * @return Product
     * @throws JsonException
     * @throws ProductAttributeException
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
     * @throws ProductAttributeException
     */
    protected function fillProduct(Product $product, Command $command) : void
    {
        $product->market                          = $command->market;
        $product->owner                           = $command->owner;
        $product->supplier                        = $command->supplier;
        $product->product_type                    = $command->productType;
        $product->is_alone_order                  = $command->isAloneOrder;
        $product->is_pre_sale                     = $command->isPreSale;
        $product->title                           = $command->title;
        $product->slogan                          = $command->slogan;
        $product->image                           = $command->image;
        $product->barcode                         = $command->barcode;
        $product->outer_id                        = $command->outerId;
        $product->is_customized                   = $command->isCustomized;
        $product->is_multiple_spec                = $command->isMultipleSpec;
        $product->is_brand_new                    = $command->isBrandNew;
        $product->sort                            = $command->sort;
        $product->unit                            = $command->unit;
        $product->unit_quantity                   = $command->unitQuantity;
        $product->spu_id                          = $command->spuId;
        $product->category_id                     = $command->categoryId;
        $product->brand_id                        = $command->brandId;
        $product->model_code                   = $command->modelCode;
        $product->product_group_id                = $command->productGroupId;
        $product->delivery_methods                = $command->deliveryMethods;
        $product->freight_payer                   = $command->freightPayer;
        $product->freight_template_id             = $command->freightTemplateId;
        $product->min_limit                       = $command->minLimit;
        $product->max_limit                       = $command->maxLimit;
        $product->step_limit                      = $command->stepLimit;
        $product->sub_stock                       = $command->subStock;
        $product->delivery_time                   = $command->deliveryTime;
        $product->order_quantity_limit_type       = $command->orderQuantityLimitType;
        $product->order_quantity_limit_num        = $command->orderQuantityLimitNum;
        $product->vip                             = $command->vip;
        $product->gift_point                      = $command->giftPoint;
        $product->is_hot                          = $command->isHot;
        $product->is_new                          = $command->isNew;
        $product->is_best                         = $command->isBest;
        $product->is_benefit                      = $command->isBenefit;
        $product->supplier_product_id             = $command->supplierProductId;
        $product->start_sale_time                 = $command->startSaleTime;
        $product->end_sale_time                   = $command->endSaleTime;
        $product->tax_rate                        = $command->taxRate;
        $product->extension->id                   = $product->id;
        $product->extension->after_sales_services = blank($command->afterSalesServices) ? $command::defaultAfterSalesServices() : $command->afterSalesServices;
        $product->extension->videos               = $command->videos;
        $product->extension->images               = $command->images;
        $product->extension->keywords             = $command->keywords;
        $product->extension->description          = $command->description;
        $product->extension->tips                 = $command->tips;
        $product->extension->detail               = $command->detail;
        $product->extension->remarks              = $command->remarks;
        $product->extension->tools                = $command->tools;
        $product->extension->extra                = $command->extra;
        $product->extension->form                 = $command->form;
        $product->extension->basic_attrs          = $this->attributeValidateService->basicProps($command->basicProps?->toArray() ?? []);
        $product->extension->customize_attrs      = $command->customizeProps?->toArray() ?? [];


        $product->setRelation('extendProductGroups', collect($command->extendProductGroups));

        $product->setRelation('tags', collect($command->tags));

        $product->setRelation('services', collect($command->services));

        $product->setStatus($command->status);
    }


    /**
     * @param  Product  $product
     * @param  Command  $command
     *
     * @return void
     * @throws JsonException
     * @throws ProductAttributeException
     */
    protected function handleMultipleSpec(Product $product, Command $command) : void
    {
        // 多规格区别处理
        switch ($command->isMultipleSpec) {
            case true: // 多规格

                $saleProps                      = $this->attributeValidateService->saleProps($command->saleProps->toArray());
                $product->extension->sale_attrs = $saleProps->toArray();
                // 验证规格


                $this->attributeValidateService->validateSkus($saleProps, $command->skus);
                $command->skus?->each(function (Sku $skuData) use ($product) {
                    $sku = $product->skus
                               ->where('properties_sequence', $skuData->propertiesSequence)
                               ->first() ?? ProductSku::make();
                    if (!$sku?->id) {
                        $sku->setUniqueIds();
                    }
                    $this->fillSku($sku, $skuData);
                    $product->addSku($sku);
                });


                // 统计项

                $product->price        = $product->skus->where('properties_sequence', '<>',
                    $product::$defaultPropertiesSequence)->min('price');
                $product->market_price = $product->skus->where('properties_sequence', '<>',
                    $product::$defaultPropertiesSequence)->min('market_price');
                $product->cost_price   = $product->skus->where('properties_sequence', '<>',
                    $product::$defaultPropertiesSequence)->min('cost_price');
                $product->safety_stock = $product->skus->where('properties_sequence', '<>',
                    $product::$defaultPropertiesSequence)->sum('safety_stock');


                // 加入默认规格
                $defaultSku = $product->skus->where('properties_sequence',
                    $product::$defaultPropertiesSequence)->first() ?? $this->defaultSku($product);
                $this->setDefaultSku($product, $defaultSku);
                $defaultSku->setDeleted();

                $product->addSku($defaultSku);

                break;
            case false: // 单规格
                $product->price                 = $command->price;
                $product->cost_price            = $command->costPrice;
                $product->market_price          = $command->marketPrice;
                $product->safety_stock          = $command->safetyStock;
                $product->extension->sale_attrs = [];


                $defaultSku = $product->skus
                                  ->where('properties_sequence', $product::$defaultAttributesSequence)
                                  ->first()
                              ?? $this->defaultSku($product);

                $this->setDefaultSku($product, $defaultSku);
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

    protected function defaultSku(Product $product) : ProductSku
    {

        $sku                      = new ProductSku();
        $sku->id                  = $product->id;
        $sku->properties_sequence = $product::$defaultAttributesSequence;
        $sku->properties_name     = $product::$defaultAttributesName;
        return $sku;
    }


    protected function setDefaultSku(Product $product, ProductSku $sku)
    {
        $sku->status          = ProductStatusEnum::ON_SALE;
        $sku->deleted_at      = null;
        $sku->image           = $product->image;
        $sku->barcode         = $product->barcode;
        $sku->outer_id        = $product->outer_id;
        $sku->price           = $product->price ?? 0;
        $sku->cost_price      = $product->cost_price ?? null;
        $sku->market_price    = $product->market_price ?? null;
        $sku->safety_stock    = $product->safety_stock ?? 0;
        $sku->image           = $product->image;
        $sku->barcode         = $product->barcode;
        $sku->outer_id        = $product->outer_id;
        $sku->supplier_sku_id = null;
        $sku->weight          = $product->extension->weight;
        $sku->width           = $product->extension->width;
        $sku->height          = $product->extension->height;
        $sku->length          = $product->extension->length;
        $sku->size            = $product->extension->size;
    }
}
