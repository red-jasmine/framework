<?php

namespace RedJasmine\Product\Application\Attribute\Services;

use Illuminate\Support\Collection;
use JsonException;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeRepositoryInterface;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeValueRepositoryInterface;
use RedJasmine\Product\Domain\Product\Data\Sku;
use RedJasmine\Product\Domain\Product\Models\ValueObjects\Attribute;
use RedJasmine\Product\Domain\Product\Models\ValueObjects\PropValue;
use RedJasmine\Product\Domain\Product\AttributeFormatter;
use RedJasmine\Product\Exceptions\ProductAttributeException;

/**
 * 属性验证服务
 */
class ProductAttributeValidateService
{
    public function __construct(
        protected ProductAttributeRepositoryInterface $attributeRepository,
        protected ProductAttributeValueRepositoryInterface $valueRepository,
        protected AttributeFormatter $attributeFormatter,
    ) {

    }

    /**
     * 基础属性验证
     *
     * @param  array  $attributes
     *
     * @return Collection
     * @throws ProductAttributeException
     */
    public function basicProps(array $attributes = []) : Collection
    {

        $attributeModels = $this->getAttributes($attributes);

        $basicAttrs = collect();
        foreach ($attributes as $attr) {
            $basicAttr = new Attribute();
            /**
             * @var $attributeModel ProductAttribute
             */
            $attributeModel       = $attributeModels[$attr['pid']];
            $basicAttr->pid       = $attributeModel->id;
            $basicAttr->name      = $attributeModel->name;
            $basicAttr->unit      = $attributeModel->unit;
            $basicAttr->values    = collect();

            $values = $attr['values'] ?? [];

            switch ($attributeModel->type) {
                case ProductAttributeTypeEnum::TEXT:
                case ProductAttributeTypeEnum::DATE:

                    $saleAttrValue        = new PropValue();
                    $saleAttrValue->vid   = 0;
                    $saleAttrValue->name  = (string) ($values[0]['name'] ?? '');
                    $saleAttrValue->alias = (string) ($values[0]['alias'] ?? '');
                    $basicAttr->values->add($saleAttrValue);
                    if (!$this->isAllowAlias($attributeModel)) {
                        $saleAttrValue->alias = null;
                    }
                    break;
                case ProductAttributeTypeEnum::SELECT:

                    $attrValues        = $this->valueRepository->findByIdsInAttribute($basicAttr->pid,
                        collect($values)->pluck('vid')->toArray())->keyBy('id');
                    $basicAttr->values = collect();

                    foreach ($values as $value) {
                        $vid                  = $value['vid'];
                        $alias                = $value['alias'] ?? '';
                        $saleAttrValue        = new PropValue();
                        $saleAttrValue->vid   = $vid;
                        $saleAttrValue->name  = $attrValues[$saleAttrValue->vid]->name;
                        $saleAttrValue->alias = $alias;
                        if (!$this->isAllowAlias($attributeModel)) {
                            $saleAttrValue->alias = null;
                        }
                        $basicAttr->values->add($saleAttrValue);
                    }


                    break;
            }


            //
            if ($basicAttr->values->count() > 1 && !$this->isAllowMultipleValues($attributeModel)) {
                throw new ProductAttributeException('属性不支持多选!');
            }


            $basicAttrs->add($basicAttr);

        }


        return $basicAttrs;


    }

    /**
     * 获取属性
     *
     * @param  array  $attributes
     *
     * @return Collection
     * @throws ProductAttributeException
     */
    protected function getAttributes(array $attributes = []) : Collection
    {

        $pid = collect($attributes)->pluck('pid')->unique()->toArray();
        // 验证重复
        if (count($pid) !== count($attributes)) {
            throw new ProductAttributeException('属性重复');
        }
        if (blank($pid)) {
            return collect();
        }

        $attributeModels = collect($this->attributeRepository->findByIds($pid))->keyBy('id');


        if (count($pid) !== count($attributeModels)) {
            throw new ProductAttributeException('属性ID存在错误');
        }


        return $attributeModels;

    }

    /**
     * 是否允许多个值
     *
     * @param  ProductAttribute  $attribute
     *
     * @return bool
     */
    protected function isAllowMultipleValues(ProductAttribute $attribute) : bool
    {
        return $attribute->isAllowMultipleValues();
    }

    protected function isAllowAlias(ProductAttribute $attribute) : bool
    {

        return $attribute->isAllowAlias();
    }

    /**
     * @param  array  $attributes
     *
     * @return array|null
     * @throws JsonException
     * @throws ProductAttributeException
     */
    public function crossJoin(array $attributes = []) : ?array
    {

        $saleAttrs = $this->saleProps($attributes);

        if (count($attributes) <= 0) {
            return [];
        }

        $crossJoinString = $this->attributeFormatter->crossJoinToString(json_decode($saleAttrs->toJson(), true, 512,
            JSON_THROW_ON_ERROR));
        $crossJoin       = [];
        foreach ($crossJoinString as $attributeString) {
            $crossJoin[$attributeString] = $this->buildSkuName($saleAttrs, $attributeString);
        }
        return $crossJoin;

    }

    /**
     * 验证销售属性
     *
     * @param  array  $attributes
     *
     * @return Collection<Attribute>
     * @throws ProductAttributeException
     */
    public function saleProps(array $attributes = []) : Collection
    {

        $attributeModels = $this->getAttributes($attributes);


        $saleAttrs = collect();
        foreach ($attributes as $attr) {
            $saleAttr = new Attribute();
            /**
             * @var $attributeModel ProductAttribute
             */
            $attributeModel     = $attributeModels[$attr['pid']];
            $saleAttr->pid      = $attributeModel->id;
            $saleAttr->name     = $attributeModel->name;
            $saleAttr->unit     = $attributeModel->unit;
            $values             = $attr['values'] ?? [];

            // 查询属性的值
            $attrValues = $this->valueRepository->findByIdsInAttribute($saleAttr->pid,
                collect($values)->pluck('vid')->toArray())->keyBy('id');

            $saleAttr->values = collect();
            foreach ($values as $value) {

                $vid                  = $value['vid'];
                $alias                = $value['alias'] ?? '';
                $saleAttrValue        = new PropValue();
                $saleAttrValue->vid   = $vid;
                $saleAttrValue->name  = $attrValues[$saleAttrValue->vid]->name;
                $saleAttrValue->alias = $alias;


                $saleAttr->values->add($saleAttrValue);
            }

            if ($saleAttr->values->count() <= 0) {
                throw new ProductAttributeException('属性值不支持为空');
            }
            $saleAttrs->add($saleAttr);
        }
        return $saleAttrs;
    }

    /**
     * 生成规格名称
     *
     * @param  Collection  $saleAttrs
     * @param  string  $attributesString
     *
     * @return string
     * @throws ProductAttributeException
     */
    public function buildSkuName(Collection $saleAttrs, string $attributesString) : string
    {
        $attributesArray = $this->attributeFormatter->toArray($attributesString);
        $labels          = [];
        foreach ($attributesArray as $attribute) {
            $pid = $attribute['pid'];
            $vid = $attribute['vid'][0];

            $attributeItem = $saleAttrs->where('pid', $pid)->first();

            if (blank($attributeItem)) {
                throw new ProductAttributeException('属性不存在');
            }

            $value = $attributeItem->values->where('vid', $vid)->first();

            if (blank($value)) {
                throw new ProductAttributeException('属性值不存在');
            }


            $labels[] = [
                'pid'   => $attributeItem->pid,
                'vid'   => $value->vid,
                'name'  => $attributeItem->name,
                'value' => $value->name,
                'alias' => $value->alias,
            ];
        }

        return $this->attributeFormatter->toNameString($labels);
    }

    /**
     * @param  Collection<Attribute>  $saleAttrs
     * @param  Collection<Sku>  $skus
     *
     * @return Collection
     * @throws ProductAttributeException|JsonException
     */
    public function validateSkus(Collection $saleAttrs, Collection $skus) : Collection
    {

        $crossJoinString = $this->attributeFormatter->crossJoinToString(json_decode($saleAttrs->toJson(), true, 512,
            JSON_THROW_ON_ERROR));

        $skuAttributes = $skus->pluck('propertiesSequence')->unique()->toArray();


        // 对比数量
        if (count($crossJoinString) !== count($skus)) {
            throw new ProductAttributeException('规则数量不一致');
        }

        // 验证总数量
        foreach ($skus as $sku) {
            $sku->propertiesSequence = $this->attributeFormatter->formatString($sku->propertiesSequence);
            $sku->propertiesName     = $this->buildSkuName($saleAttrs, $sku->propertiesSequence);
        }


        $diff = collect($crossJoinString)->diff($skuAttributes);


        if ($diff->count() > 0) {
            throw new ProductAttributeException('cross join too many attributes');
        }

        return $skus;
    }
}
