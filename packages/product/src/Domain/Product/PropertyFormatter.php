<?php

namespace RedJasmine\Product\Domain\Product;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RedJasmine\Product\Exceptions\ProductPropertyException;

class PropertyFormatter
{


    /**
     * 转换规格名称
     *
     * @param array $labels
     *
     * @return string
     */
    public function toNameString(array $labels) : string
    {
        $labelString = [];
        foreach ($labels as $label) {
            $labelString[] = implode(':', [ $label['name'], filled($label['alias'] ?? '') ? $label['alias'] : $label['value'] ]);
        }

        return implode(';', $labelString);
    }

    /**
     * @param string|null $value
     *
     * @return string
     * @throws ProductPropertyException
     */
    public function formatString(string $value = null) : string
    {
        if (blank($value)) {
            return '';
        }
        return $this->toString($this->toArray($value));
    }

    /**
     * @param array $value
     *
     * @return array
     * @throws ProductPropertyException
     */
    public function formatArray(array $value = []) : array
    {
        if (blank($value)) {
            return [];
        }
        return $this->toArray($this->toString($value));
    }


    /**
     * 属性笛卡尔积
     *
     * @param array{pid:int,vid:int|int[]} $props
     *
     * @return string[]
     */
    public function crossJoinToString(array $props = []) : array
    {
        $crossJoin         = $this->crossJoinToArray($props);
        $crossJoinTextList = [];
        foreach ($crossJoin as $item) {
            $crossJoinTextList[] = $this->toString($item);
        }
        return $crossJoinTextList;
    }

    /**
     * 计算规格属性的属性
     *
     * @param array $props
     *
     * @return array
     */
    public function crossJoinToArray(array $props = []) : array
    {
        $skuProperties = [];
        foreach ($props as $item) {
            $pid            = (int)$item['pid'];
            $values         = $item['values'] ?? [];
            $propertyValues = [];
            foreach ($values as $value) {
                $vid              = $value['vid'] ?? null;
                $alias            = $value['alias'] ?? null;
                $propertyValues[] = [ 'pid' => $pid, 'vid' => (int)$vid ];
            }
            $skuProperties[] = $propertyValues;
        }
        return Arr::crossJoin(...$skuProperties);
    }


    /**
     * @param array{pid:int,vid:int|int[]} $props
     *
     * @return string
     */
    public function toString(array $props = []) : string
    {
        if (blank($props)) {
            return '';
        }
        $properties = [];
        foreach ($props as $item) {
            $pid    = (int)$item['pid'];
            $values = $item['vid'] ?? null;
            if (!is_array($values)) {
                $values = [ $values ];
            }
            $newValues = [];
            foreach ($values as &$vid) {
                if (filled($vid)) {
                    $newValues[] = (int)$vid;
                }
            }
            asort($newValues);
            $properties[$pid] = $pid . ':' . implode(',', $values);
        }
        asort($properties);
        return implode(';', $properties);

    }


    /**
     * @param string $propsString
     *
     * @return array
     * @throws ProductPropertyException
     */
    public function toArray(string $propsString = '') : array
    {

        if (blank($propsString)) {
            return [];
        }
        $properties = [];
        $props      = explode(';', $propsString);

        try {
            foreach ($props as $property) {
                if (blank($property)) {
                    continue;
                }
                [ $pid, $values ] = explode(':', $property);
                $propertyItem        = [];
                $pid                 = (int)$pid;
                $propertyItem['pid'] = (int)$pid;
                $propertyItem['vid'] = [];

                $itemPropValues = explode(',', $values);
                foreach ($itemPropValues as $itemPropValue) {
                    if (blank($itemPropValue)) {
                        continue;
                    }
                    $propertyItem['vid'][] = (int)$itemPropValue;
                }
                $propertyItem['vid'] = array_unique($propertyItem['vid']);
                asort($propertyItem['vid']);

                $properties[$pid] = $propertyItem;

            }
            asort($properties);
        } catch (\Throwable $throwable) {
            throw new ProductPropertyException('属性格式错误');
        }


        return array_values($properties);


    }

}
