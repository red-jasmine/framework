<?php

namespace RedJasmine\Product\Domain\Price\Services;

use Illuminate\Support\Collection;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;

class PriceMatcher
{
    /**
     * 匹配最佳价格
     *
     * @param Collection|array $prices 价格列表
     * @param string $market 市场
     * @param string $store 门店
     * @param string $userLevel 用户等级
     * @return ProductVariantPrice|null
     */
    public function match(Collection|array $prices, string $market, string $store, string $userLevel): ?ProductVariantPrice
    {
        if ($prices instanceof Collection) {
            $prices = $prices->all();
        }

        if (empty($prices)) {
            return null;
        }

        // 所有价格都是有效的（创建即生效），直接计算匹配分数
        $scoredPrices = [];
        foreach ($prices as $price) {
            $score = $this->calculateMatchScore($price, $market, $store, $userLevel);
            $scoredPrices[] = [
                'price' => $price,
                'score' => $score,
                'priority' => $price->priority,
            ];
        }

        // 排序：优先级 > 匹配分数
        usort($scoredPrices, function ($a, $b) {
            // 先按优先级排序（数字越大越优先）
            if ($a['priority'] !== $b['priority']) {
                return $b['priority'] <=> $a['priority'];
            }
            // 再按匹配分数排序（分数越高越优先）
            return $b['score'] <=> $a['score'];
        });

        return $scoredPrices[0]['price'] ?? null;
    }

    /**
     * 计算匹配分数
     *
     * @param ProductVariantPrice $price 价格对象
     * @param string $market 市场
     * @param string $store 门店
     * @param string $userLevel 用户等级
     * @return int 匹配分数
     */
    public function calculateMatchScore(ProductVariantPrice $price, string $market, string $store, string $userLevel): int
    {
        $score = 0;

        // market 维度匹配（非通配符 +1000 分）
        if ($price->market !== '*' && $price->market === $market) {
            $score += 1000;
        }

        // store 维度匹配（非通配符 +100 分）
        if ($price->store !== '*' && $price->store === $store) {
            $score += 100;
        }

        // user_level 维度匹配（非通配符 +1 分）
        if ($price->user_level !== '*' && $price->user_level === $userLevel) {
            $score += 1;
        }

        return $score;
    }
}

