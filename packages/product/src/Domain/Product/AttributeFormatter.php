<?php

namespace RedJasmine\Product\Domain\Product;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RedJasmine\Product\Exceptions\ProductAttributeException;

class AttributeFormatter
{


    /**
     * 转换规格名称
     *
     * @param  array  $labels
     *
     * @return string
     */
    public function toNameString(array $labels) : string
    {
        $labelString = [];
        foreach ($labels as $label) {
            $attrLabels = [$label['name'], $label['value']];
            if (filled($label['alias'] ?? null)) {
                $attrLabels = [$label['name'], "{$label['value']}({$label['alias']})"];
            }
            $labelString[] = implode(':', $attrLabels);
        }

        return implode(';', $labelString);
    }

    /**
     * @param  string|null  $value
     *
     * @return string
     * @throws ProductAttributeException
     */
    public function formatString(string $value = null) : string
    {
        if (blank($value)) {
            return '';
        }
        return $this->toString($this->toArray($value));
    }

    /**
     * @param  array  $value
     *
     * @return array
     * @throws ProductAttributeException
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
     * @param  array{aid:int,vid:int|int[]}  $attributes
     *
     * @return string[]
     */
    public function crossJoinToString(array $attributes = []) : array
    {
        $crossJoin         = $this->crossJoinToArray($attributes);
        $crossJoinTextList = [];
        foreach ($crossJoin as $item) {
            $crossJoinTextList[] = $this->toString($item);
        }
        return $crossJoinTextList;
    }

    /**
     * 计算规格属性的属性
     *
     * @param  array  $attributes
     *
     * @return array
     */
    public function crossJoinToArray(array $attributes = []) : array
    {
        $skuAttributes = [];
        foreach ($attributes as $item) {
            $aid             = (int) $item['aid'];
            $values          = $item['values'] ?? [];
            $attributeValues = [];
            foreach ($values as $value) {
                $vid               = $value['vid'] ?? null;
                $alias             = $value['alias'] ?? null;
                $attributeValues[] = ['aid' => $aid, 'vid' => (int) $vid];
            }
            $skuAttributes[] = $attributeValues;
        }
        return Arr::crossJoin(...$skuAttributes);
    }


    /**
     * @param  array{aid:int,vid:int|int[]}  $attributes
     *
     * @return string
     */
    public function toString(array $attributes = []) : string
    {
        if (blank($attributes)) {
            return '';
        }
        $attributeList = [];
        foreach ($attributes as $item) {
            $aid    = (int) $item['aid'];
            $values = $item['vid'] ?? null;
            if (!is_array($values)) {
                $values = [$values];
            }
            $newValues = [];
            foreach ($values as &$vid) {
                if (filled($vid)) {
                    $newValues[] = (int) $vid;
                }
            }
            asort($newValues);
            $attributeList[$aid] = $aid.':'.implode(',', $values);
        }
        asort($attributeList);
        return implode(';', $attributeList);

    }


    /**
     * @param  string  $attributesString
     *
     * @return array
     * @throws ProductAttributeException
     */
    public function toArray(string $attributesString = '') : array
    {

        if (blank($attributesString)) {
            return [];
        }
        $attributeList = [];
        $attributes    = explode(';', $attributesString);

        try {
            foreach ($attributes as $attribute) {
                if (blank($attribute)) {
                    continue;
                }
                [$aid, $values] = explode(':', $attribute);
                $attributeItem        = [];
                $aid                  = (int) $aid;
                $attributeItem['aid'] = (int) $aid;
                $attributeItem['vid'] = [];

                $itemAttrValues = explode(',', $values);
                foreach ($itemAttrValues as $itemAttrValue) {
                    if (blank($itemAttrValue)) {
                        continue;
                    }
                    $attributeItem['vid'][] = (int) $itemAttrValue;
                }
                $attributeItem['vid'] = array_unique($attributeItem['vid']);
                asort($attributeItem['vid']);

                $attributeList[$aid] = $attributeItem;

            }
            asort($attributeList);
        } catch (\Throwable $throwable) {
            throw new ProductAttributeException('属性格式错误');
        }


        return array_values($attributeList);


    }

}

