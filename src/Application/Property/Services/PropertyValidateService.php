<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Support\Collection;
use JsonException;
use RedJasmine\Product\Domain\Product\Models\ValueObjects\Property;
use RedJasmine\Product\Domain\Product\Models\ValueObjects\PropValue;
use RedJasmine\Product\Domain\Product\PropertyFormatter;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueReadRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;

/**
 * 属性验证服务
 */
class PropertyValidateService
{
    public function __construct(
        protected ProductPropertyReadRepositoryInterface $propertyReadRepository,
        protected ProductPropertyValueReadRepositoryInterface $valueReadRepository,
        protected PropertyFormatter $propertyFormatter,
    ) {

    }

    /**
     * 基础属性验证
     *
     * @param  array  $props
     *
     * @return Collection
     * @throws ProductPropertyException
     */
    public function basicProps(array $props = []) : Collection
    {

        $properties = $this->getProperties($props);

        $basicProps = collect();
        foreach ($props as $prop) {
            $basicProp = new Property();
            /**
             * @var $property ProductProperty
             */
            $property          = $properties[$prop['pid']];
            $basicProp->pid    = $property->id;
            $basicProp->name   = $property->name;
            $basicProp->unit   = $property->unit;
            $basicProp->values = collect();

            $values = $prop['values'] ?? [];

            switch ($property->type) {
                case PropertyTypeEnum::TEXT:
                case PropertyTypeEnum::DATE:

                    $salePropValue        = new PropValue();
                    $salePropValue->vid   = 0;
                    $salePropValue->name  = (string) ($values[0]['name'] ?? '');
                    $salePropValue->alias = (string) ($values[0]['alias'] ?? '');
                    $basicProp->values->add($salePropValue);
                    break;
                case PropertyTypeEnum::SELECT:
                case PropertyTypeEnum::MULTIPLE:

                    $propValues        = $this->valueReadRepository->findByIdsInProperty($basicProp->pid,
                        collect($values)->pluck('vid')->toArray())->keyBy('id');
                    $basicProp->values = collect();

                    foreach ($values as $value) {
                        $vid   = $value['vid'];
                        $alias = $value['alias'] ?? '';

                        $salePropValue        = new PropValue();
                        $salePropValue->vid   = $vid;
                        $salePropValue->name  = $propValues[$salePropValue->vid]->name;
                        $salePropValue->alias = $alias;

                        $basicProp->values->add($salePropValue);
                    }

                    if ($basicProp->values->count() > 1 && !$this->isAllowMultipleValues($property)) {
                        // TODO 优化提示
                        throw new ProductPropertyException('属性不支持多选!');
                    }

                    break;
            }
            $basicProps->add($basicProp);

        }


        return $basicProps;


    }

    /**
     * 获取属性
     *
     * @param  array  $props
     *
     * @return Collection
     * @throws ProductPropertyException
     */
    protected function getProperties(array $props = []) : Collection
    {

        $pid = collect($props)->pluck('pid')->unique()->toArray();
        // 验证重复
        if (count($pid) !== count($props)) {
            throw new ProductPropertyException('属性重复');
        }
        if (blank($pid)) {
            return collect();
        }

        $properties = collect($this->propertyReadRepository->findByIds($pid))->keyBy('id');


        if (count($pid) !== count($properties)) {
            throw new ProductPropertyException('属性ID存在错误');
        }


        return $properties;

    }

    /**
     * 是否允许多个值
     *
     * @param  ProductProperty  $property
     *
     * @return bool
     */
    protected function isAllowMultipleValues(ProductProperty $property) : bool
    {
        return $property->type === PropertyTypeEnum::MULTIPLE;
    }

    /**
     * @param  array  $props
     *
     * @return array|null
     * @throws JsonException
     * @throws ProductPropertyException
     */
    public function crossJoin(array $props = []) : ?array
    {

        $saleProps = $this->saleProps($props);

        if (count($props) <= 0) {
            return [];
        }

        $crossJoinString = $this->propertyFormatter->crossJoinToString(json_decode($saleProps->toJson(), true, 512,
            JSON_THROW_ON_ERROR));
        $crossJoin       = [];
        foreach ($crossJoinString as $properties) {
            $crossJoin[$properties] = $this->buildSkuName($saleProps, $properties);
        }
        return $crossJoin;

    }

    /**
     * 验证销售属性
     *
     * @param  array  $props
     *
     * @return Collection<Property>
     * @throws ProductPropertyException
     */
    public function saleProps(array $props = []) : Collection
    {

        $properties = $this->getProperties($props);


        $saleProps = collect();
        foreach ($props as $prop) {
            $saleProp = new Property();
            /**
             * @var $property ProductProperty
             */
            $property       = $properties[$prop['pid']];
            $saleProp->pid  = $property->id;
            $saleProp->name = $property->name;
            $saleProp->unit = $property->unit;
            $values         = $prop['values'] ?? [];

            // 查询属性的值
            $propValues = $this->valueReadRepository->findByIdsInProperty($saleProp->pid,
                collect($values)->pluck('vid')->toArray())->keyBy('id');

            $saleProp->values = collect();
            foreach ($values as $value) {

                $vid                  = $value['vid'];
                $alias                = $value['alias'] ?? '';
                $salePropValue        = new PropValue();
                $salePropValue->vid   = $vid;
                $salePropValue->name  = $propValues[$salePropValue->vid]->name;
                $salePropValue->alias = $alias;


                $saleProp->values->add($salePropValue);
            }

            if ($saleProp->values->count() <= 0) {
                throw new ProductPropertyException('属性值不支持为空');
            }
            $saleProps->add($saleProp);
        }
        return $saleProps;
    }

    /**
     * 生成规格名称
     *
     * @param  Collection  $saleProps
     * @param  string  $properties
     *
     * @return string
     * @throws ProductPropertyException
     */
    public function buildSkuName(Collection $saleProps, string $properties) : string
    {
        $propertiesArray = $this->propertyFormatter->toArray($properties);
        $labels          = [];
        foreach ($propertiesArray as $property) {
            $pid = $property['pid'];
            $vid = $property['vid'][0];

            $property = $saleProps->where('pid', $pid)->first();

            if (blank($property)) {
                throw new ProductPropertyException('属性不存在');
            }

            $value = $property->values->where('vid', $vid)->first();

            if (blank($value)) {
                throw new ProductPropertyException('属性值不存在');
            }


            $labels[] = [
                'pid'   => $property->pid,
                'vid'   => $value->vid,
                'name'  => $property->name,
                'value' => $value->name,
                'alias' => $value->alias,
            ];
        }

        return $this->propertyFormatter->toNameString($labels);
    }

    /**
     * @param  Collection<Property>  $saleProps
     * @param  Collection<\RedJasmine\Product\Domain\Product\Data\Sku>  $skus
     *
     * @return Collection
     * @throws ProductPropertyException|JsonException
     */
    public function validateSkus(Collection $saleProps, Collection $skus) : Collection
    {

        $crossJoinString = $this->propertyFormatter->crossJoinToString(json_decode($saleProps->toJson(), true, 512,
            JSON_THROW_ON_ERROR));

        $skuProperties = $skus->pluck('properties')->unique()->toArray();


        // 对比数量
        if (count($crossJoinString) !== count($skus)) {
            throw new ProductPropertyException('规则数量不一致');
        }

        // 验证总数量
        foreach ($skus as $sku) {
            $sku->properties     = $this->propertyFormatter->formatString($sku->properties);
            $sku->propertiesName = $this->buildSkuName($saleProps, $sku->properties);
        }


        $diff = collect($crossJoinString)->diff($skuProperties);


        if ($diff->count() > 0) {
            throw new ProductPropertyException('cross join too many properties');
        }

        return $skus;
    }
}
