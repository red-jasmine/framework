<?php

namespace RedJasmine\Product\Domain\Product\Transformer;

use JsonException;
use RedJasmine\Product\Domain\Attribute\Services\ProductAttributeValidateService;
use RedJasmine\Product\Domain\Media\Models\Enums\MediaTypeEnum;
use RedJasmine\Product\Domain\Media\Models\ProductMedia;
use RedJasmine\Product\Domain\Product\Data\Product as Command;
use RedJasmine\Product\Domain\Product\Data\ProductTranslation as ProductTranslationData;
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

        $this->handleTranslations($product, $command);

        $this->handleMedia($product, $command);

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
        $product->market           = $command->market;
        $product->owner            = $command->owner;
        $product->product_type     = $command->productType;
        $product->is_alone_order   = $command->isAloneOrder;
        $product->is_pre_sale      = $command->isPreSale;
        $product->title            = $command->title;
        $product->slug             = $command->slug;
        $product->spu              = $command->spu;
        $product->is_customized    = $command->isCustomized;
        $product->is_brand_new     = $command->isBrandNew;
        $product->sort             = $command->sort;
        $product->category_id      = $command->categoryId;
        $product->brand_id         = $command->brandId;
        $product->model_code       = $command->modelCode;
        $product->product_group_id = $command->productGroupId;
        $product->shipping_types   = $command->shippingTypes;
        $product->min_limit        = $command->minLimit;
        $product->max_limit        = $command->maxLimit;
        $product->step_limit       = $command->stepLimit;
        $product->delivery_time    = $command->deliveryTime;
        $product->vip              = $command->vip;
        $product->gift_point       = $command->giftPoint;
        // 设置商品货币
        $product->currency = $command->currency;


        $product->extension->id                   = $product->id;
        $product->extension->after_sales_services = blank($command->afterSalesServices) ? $command::defaultAfterSalesServices() : $command->afterSalesServices;
        $product->extension->freight_templates    = $command->freightTemplates;
        $product->extension->meta_title           = $command->metaTitle;
        $product->extension->meta_keywords        = $command->metaKeywords;
        $product->extension->meta_description     = $command->metaDescription;
        $product->extension->slogan               = $command->slogan;
        $product->extension->tips                 = $command->tips;
        $product->extension->description          = $command->description;
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

                    // 处理规格媒体资源
                    $this->handleModelMedia($variant, $variantData->media);
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
        $variant->status           = $variantData->status;
        $variant->deleted_at       = null;

        $variant->currency = $product->currency;
        // 同步变体货币：优先从价格 Money 对象中获取货币，如果没有则从商品表继承

    }

    /**
     * @param  Product|ProductVariant  $model
     * @param  \RedJasmine\Product\Domain\Product\Data\ProductMedia[]  $mediaCollect
     *
     * @return void
     */
    protected function handleModelMedia(Product|ProductVariant $model, array $mediaCollect = []) : void
    {
        $model->media;
        foreach ($model->media as $media) {
            $media->deleted_at = $media->deleted_at ?? now();
        }

        foreach ($mediaCollect as $media) {
            $mediaModel             = $model->media->where('path', $media->path)->first() ?? new ProductMedia();
            $mediaModel->deleted_at = null;
            $mediaModel->media_type = MediaTypeEnum::IMAGE; // 判断媒体类型
            $mediaModel->path       = $media->path;
            $mediaModel->is_primary = $media->isPrimary;
            $mediaModel->position   = $media->position;
            $model->addMedia($mediaModel);
            // 冗余主图
            if ($mediaModel->is_primary) {
                $model->image = $media->path;
            }
        }
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

    /**
     * 处理商品多语言翻译
     *
     * @param  Product  $product
     * @param  Command  $command
     *
     * @return void
     */
    protected function handleTranslations(Product $product, Command $command) : void
    {
        // 如果商品已存在，需要处理删除的翻译
        if ($product->exists) {
            // 获取表单提交的翻译语言列表


            // 获取数据库中现有的未删除翻译
            $existingTranslations = $product->translations()
                                            ->withTrashed()
                                            ->get();

            $product->setRelation('translations', $existingTranslations);


            // 软删除表单中不存在的翻译
            foreach ($product->translations as $translation) {
                $translation->deleted_at = now(); // 使用软删除
            }
        }

        // 如果没有翻译数据，直接返回
        if (empty($command->translations)) {
            return;
        }


        // 遍历翻译数据，设置到商品模型中
        foreach ($command->translations as $translationData) {
            // 检查是否已存在该语言的翻译（包括已软删除的）
            $existingTranslation = $product->translations->where('locale', $translationData->locale)
                                                         ->first();

            // 如果存在已软删除的翻译，先恢复
            if ($existingTranslation) {
                $existingTranslation->deleted_at = null;
            }

            // 将 ProductTranslation DTO 转换为数组
            $translationAttributes = $this->prepareTranslationAttributes($translationData);

            // 使用 Product 模型的 setTranslation 方法设置翻译
            $product->translateOrNew($translationData->locale)->fill($translationAttributes);

        }
    }

    /**
     * 准备翻译属性数组
     *
     * @param  ProductTranslationData  $translationData
     *
     * @return array
     */
    protected function prepareTranslationAttributes(ProductTranslationData $translationData) : array
    {
        $attributes = [
            'title'              => $translationData->title,
            'slogan'             => $translationData->slogan,
            'description'        => $translationData->description,
            'meta_title'         => $translationData->metaTitle,
            'meta_keywords'      => $translationData->metaKeywords,
            'meta_description'   => $translationData->metaDescription,
            'translation_status' => $translationData->translationStatus->value,
        ];

        // 如果翻译状态为已翻译，设置翻译时间
        if ($translationData->translationStatus->value === 'translated' ||
            $translationData->translationStatus->value === 'reviewed') {
            $attributes['translated_at'] = now();
        }

        // 如果翻译状态为已审核，设置审核时间
        if ($translationData->translationStatus->value === 'reviewed') {
            $attributes['reviewed_at'] = now();
        }

        return $attributes;
    }

    /**
     * 处理媒体
     *
     * @param  Product  $product
     * @param  Command  $command
     *
     * @return void
     */
    public function handleMedia(Product $product, Command $command) : void
    {
        // 处理产品级别 媒体
        $product->media;

        $this->handleModelMedia($product, $command->media);


    }

}
