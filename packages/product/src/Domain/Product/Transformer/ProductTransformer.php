<?php

namespace RedJasmine\Product\Domain\Product\Transformer;

use JsonException;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValidateService;
use RedJasmine\Product\Domain\Product\Data\Product as Command;
use RedJasmine\Product\Domain\Product\Data\Sku;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductVariant;
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

        $this->handleVariants($product, $command);

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
        $product->market         = $command->market;
        $product->owner          = $command->owner;
        $product->supplier       = $command->supplier;
        $product->product_type   = $command->productType;
        $product->is_alone_order = $command->isAloneOrder;
        $product->is_pre_sale    = $command->isPreSale;
        $product->title          = $command->title;
        $product->slogan         = $command->slogan;
        $product->image          = $command->image;
        $product->barcode        = $command->barcode;
        $product->outer_id       = $command->outerId;
        $product->is_customized  = $command->isCustomized;
        $product->has_variants   = $command->hasVariants;
        $product->is_brand_new   = $command->isBrandNew;
        $product->sort           = $command->sort;
        $product->unit           = $command->unit;
        $product->unit_quantity  = $command->unitQuantity;
        //$product->spu_id                          = $command->spuId;
        $product->category_id               = $command->categoryId;
        $product->brand_id                  = $command->brandId;
        $product->model_code                = $command->modelCode;
        $product->product_group_id          = $command->productGroupId;
        $product->delivery_methods          = $command->deliveryMethods;
        $product->freight_payer             = $command->freightPayer;
        $product->freight_template_id       = $command->freightTemplateId;
        $product->min_limit                 = $command->minLimit;
        $product->max_limit                 = $command->maxLimit;
        $product->step_limit                = $command->stepLimit;
        $product->sub_stock                 = $command->subStock;
        $product->delivery_time             = $command->deliveryTime;
        $product->order_quantity_limit_type = $command->orderQuantityLimitType;
        $product->order_quantity_limit_num  = $command->orderQuantityLimitNum;
        $product->vip                       = $command->vip;
        $product->gift_point                = $command->giftPoint;
        $product->is_hot                    = $command->isHot;
        $product->is_new                    = $command->isNew;
        $product->is_best                   = $command->isBest;
        $product->is_benefit                = $command->isBenefit;
        $product->supplier_product_id       = $command->supplierProductId;
        $product->start_sale_time           = $command->startSaleTime;
        $product->end_sale_time             = $command->endSaleTime;
        //$product->tax_rate                        = $command->taxRate;
        $product->extension->id                   = $product->id;
        $product->extension->after_sales_services = blank($command->afterSalesServices) ? $command::defaultAfterSalesServices() : $command->afterSalesServices;
        $product->extension->videos               = $command->videos;
        $product->extension->images               = $command->images;
        $product->extension->meta_title           = $command->metaTitle;
        $product->extension->meta_keywords        = $command->metaKeywords;
        $product->extension->meta_description     = $command->metaDescription;
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
    protected function handleVariants(Product $product, Command $command) : void
    {
        // 多规格区别处理
        switch ($command->hasVariants) {
            case true: // 多规格

                $saleProps                      = $this->attributeValidateService->saleProps($command->saleProps->toArray());
                $product->extension->sale_attrs = $saleProps->toArray();
                // 验证规格


                $this->attributeValidateService->validateSkus($saleProps, $command->variants);
                $command->variants?->each(function (Sku $skuData) use ($product) {
                    $sku = $product->variants
                               ->where('properties_sequence', $skuData->propertiesSequence)
                               ->first() ?? ProductVariant::make();
                    if (!$sku?->id) {
                        $sku->setUniqueIds();
                    }
                    $this->fillVariant($sku, $skuData);
                    $product->addVariant($sku);
                });


                // 统计项

                $product->price        = $product->variants->where('properties_sequence', '<>',
                    $product::$defaultPropertiesSequence)->min('price');
                $product->market_price = $product->variants->where('properties_sequence', '<>',
                    $product::$defaultPropertiesSequence)->min('market_price');
                $product->cost_price   = $product->variants->where('properties_sequence', '<>',
                    $product::$defaultPropertiesSequence)->min('cost_price');
                $product->safety_stock = $product->variants->where('properties_sequence', '<>',
                    $product::$defaultPropertiesSequence)->sum('safety_stock');


                // 加入默认规格
                $defaultSku = $product->variants->where('properties_sequence',
                    $product::$defaultPropertiesSequence)->first() ?? $this->defaultVariant($product);
                $this->setDefaultVariant($product, $defaultSku);
                $defaultSku->setDeleted();

                $product->addVariant($defaultSku);

                break;
            case false: // 单规格
                $product->price                 = $command->price;
                $product->cost_price            = $command->costPrice;
                $product->market_price          = $command->marketPrice;
                $product->safety_stock          = $command->safetyStock;
                $product->extension->sale_attrs = [];


                $defaultSku = $product->variants
                                  ->where('properties_sequence', $product::$defaultAttributesSequence)
                                  ->first()
                              ?? $this->defaultVariant($product);

                $this->setDefaultVariant($product, $defaultSku);
                $defaultSku->setOnSale();
                $product->addVariant($defaultSku);
                break;
        }
    }

    protected function fillVariant(ProductVariant $variant, Sku $skuData) : void
    {
        $variant->properties_sequence = $skuData->propertiesSequence;
        $variant->properties_name     = $skuData->propertiesName;
        $variant->image               = $skuData->image;
        $variant->barcode             = $skuData->barcode;
        $variant->outer_id            = $skuData->outerId;
        $variant->price               = $skuData->price;
        $variant->safety_stock        = $skuData->safetyStock;
        $variant->market_price        = $skuData->marketPrice;
        $variant->cost_price          = $skuData->costPrice;
        $variant->supplier_sku_id     = $skuData->supplierSkuId;
        $variant->weight              = $skuData->weight;
        $variant->width               = $skuData->width;
        $variant->height              = $skuData->height;
        $variant->length              = $skuData->length;
        $variant->volume              = $skuData->volume;
        $variant->status              = $skuData->status;
        $variant->deleted_at          = null;
    }

    protected function defaultVariant(Product $product) : ProductVariant
    {

        $variant                      = new ProductVariant();
        $variant->id                  = $product->id;
        $variant->properties_sequence = $product::$defaultAttributesSequence;
        $variant->properties_name     = $product::$defaultAttributesName;
        return $variant;
    }


    protected function setDefaultVariant(Product $product, ProductVariant $variant)
    {
        $variant->status          = ProductStatusEnum::ON_SALE;
        $variant->deleted_at      = null;
        $variant->image           = $product->image;
        $variant->barcode         = $product->barcode;
        $variant->outer_id        = $product->outer_id;
        $variant->price           = $product->price ?? 0;
        $variant->cost_price      = $product->cost_price ?? null;
        $variant->market_price    = $product->market_price ?? null;
        $variant->safety_stock    = $product->safety_stock ?? 0;
        $variant->image           = $product->image;
        $variant->barcode         = $product->barcode;
        $variant->outer_id        = $product->outer_id;
        $variant->supplier_sku_id = null;

        $variant->weight          = $product->extension->weight;
        $variant->width           = $product->extension->width;
        $variant->height          = $product->extension->height;
        $variant->length          = $product->extension->length;
        $variant->volume          = $product->extension->volume;
    }
}
