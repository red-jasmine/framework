<?php

namespace RedJasmine\Product\Domain\Product\Transformer;

use JsonException;
use RedJasmine\Product\Domain\Attribute\Services\ProductAttributeValidateService;
use RedJasmine\Product\Domain\Product\Data\Product as Command;
use RedJasmine\Product\Domain\Product\Data\Variant;
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
        $product->market                    = $command->market;
        $product->owner                     = $command->owner;
        $product->product_type              = $command->productType;
        $product->is_alone_order            = $command->isAloneOrder;
        $product->is_pre_sale               = $command->isPreSale;
        $product->title                     = $command->title;
        $product->slogan                    = $command->slogan;
        $product->spu                       = $command->spu;
        $product->image                     = $command->image;
        $product->is_customized             = $command->isCustomized;
        $product->is_brand_new              = $command->isBrandNew;
        $product->sort                      = $command->sort;
        $product->category_id               = $command->categoryId;
        $product->brand_id                  = $command->brandId;
        $product->model_code                = $command->modelCode;
        $product->product_group_id          = $command->productGroupId;
        $product->shipping_types            = $command->shippingTypes;
        $product->min_limit                 = $command->minLimit;
        $product->max_limit                 = $command->maxLimit;
        $product->step_limit                = $command->stepLimit;
        $product->delivery_time             = $command->deliveryTime;
        $product->order_quantity_limit_type = $command->orderQuantityLimitType;
        $product->order_quantity_limit_num  = $command->orderQuantityLimitNum;
        $product->vip                       = $command->vip;
        $product->gift_point                = $command->giftPoint;
        $product->is_hot                    = $command->isHot;
        $product->is_new                    = $command->isNew;
        $product->is_best                   = $command->isBest;
        $product->is_benefit                = $command->isBenefit;
        // 设置商品货币
        $product->currency = $command->currency;


        $product->extension->id                   = $product->id;
        $product->extension->after_sales_services = blank($command->afterSalesServices) ? $command::defaultAfterSalesServices() : $command->afterSalesServices;
        $product->extension->freight_templates    = $command->freightTemplates;
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
        $product->extension->basic_attrs          = $this->attributeValidateService->basicAttrs($command->basicAttrs?->toArray() ?? []);
        $product->extension->customize_attrs      = $command->customizeAttrs?->toArray() ?? [];


        $product->has_variants = $command->hasVariants;

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


                $saleAttrs = $this->attributeValidateService->saleAttrs($command->saleAttrs->toArray());

                $product->extension->sale_attrs = $saleAttrs->toArray();
                // 验证规格


                $this->attributeValidateService->validateVariants($saleAttrs, $command->variants);


                $command->variants?->each(function (Variant $variantData) use ($product) {
                    $variant = $product->variants
                                   ->where('attrs_sequence', $variantData->attrsSequence)
                                   ->first() ?? ProductVariant::make();

                    if (!$variant?->id) {
                        $variant->setUniqueIds();
                    }
                    $this->fillVariant($variant, $variantData, $product);
                    $product->addVariant($variant);
                });

                // 加入默认规格
                $defaultVariant = $product->getDefaultVariant();
                $this->setDefaultVariant($product, $defaultVariant);
                $defaultVariant->setDeleted();

                $product->addVariant($defaultVariant);


                break;
            case false: // 单规格

                // 获取变体中的属性


                if ($command->variants->count() != 1) {
                    throw new ProductAttributeException('单变体属性数量错误');
                }
                $variantData = $command->variants->first();

                // 深圳规格属性为空
                $product->extension->sale_attrs = [];
                // 这里需要修改 单规格， 基础的规格 从 规格合集中获取
                $defaultVariant = $product->getDefaultVariant();
                // 设置变体基础信息
                $this->setDefaultVariant($product, $defaultVariant);
                $this->fillVariant($defaultVariant, $variantData, $product);

                $defaultVariant->setAvailable();

                $product->addVariant($defaultVariant);
                break;
        }


        $variants = $product->variants->whereNotIn('status', [
            ProductStatusEnum::ARCHIVED,
            ProductStatusEnum::DELETED,
        ]);
        // 统计项
        $product->price        = $variants->min('price');
        $product->market_price = $variants->min('market_price');
        $product->cost_price   = $variants->min('cost_price');


    }

    protected function fillVariant(ProductVariant $variant, Variant $variantData, Product $product) : void
    {
        $variant->attrs_sequence   = $variantData->attrsSequence;
        $variant->attrs_name       = $variantData->getAttrsName();
        $variant->sku              = $variantData->sku;
        $variant->image            = $variantData->image;
        $variant->barcode          = $variantData->barcode;
        $variant->price            = $variantData->price;
        $variant->market_price     = $variantData->marketPrice;
        $variant->cost_price       = $variantData->costPrice;
        $variant->weight           = $variantData->weight;
        $variant->weight_unit      = $variantData->weightUnit;
        $variant->width            = $variantData->width;
        $variant->height           = $variantData->height;
        $variant->length           = $variantData->length;
        $variant->volume           = $variantData->volume;
        $variant->dimension_unit   = $variantData->dimensionUnit;
        $variant->package_unit     = $variantData->packageUnit;
        $variant->package_quantity = $variantData->packageQuantity;
        $variant->status           = $variantData->status;
        $variant->deleted_at       = null;

        $variant->currency = $product->currency;
        // 同步变体货币：优先从价格 Money 对象中获取货币，如果没有则从商品表继承

    }

    protected function setDefaultVariant(Product $product, ProductVariant $variant) : void
    {
        $variant->status       = ProductStatusEnum::AVAILABLE;
        $variant->deleted_at   = null;
        $variant->image        = $product->image;
        $variant->price        = $product->price ?? 0;
        $variant->cost_price   = $product->cost_price ?? null;
        $variant->market_price = $product->market_price ?? null;
        $variant->image        = $product->image;
        // 同步变体货币：从商品表继承
        $variant->currency = $product->currency;

    }


}
