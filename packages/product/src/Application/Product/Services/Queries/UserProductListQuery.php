<?php

namespace RedJasmine\Product\Application\Product\Services\Queries;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * 用户商品列表查询
 * 
 * 根据价格维度信息查询商品列表，并自动附加价格信息
 */
class UserProductListQuery extends PaginateQuery
{
    /**
     * 商品标题（搜索）
     */
    public ?string $title = null;

    /**
     * 商品分类ID
     */
    public ?int $categoryId = null;

    /**
     * 品牌ID
     */
    public ?int $brandId = null;

    /**
     * 商品状态
     */
    public ?string $status = null;

    /**
     * 价格维度：市场
     */
    public string $market = '*';

    /**
     * 价格维度：门店
     */
    public string $store = '*';

    /**
     * 价格维度：用户等级
     */
    public string $userLevel = 'default';

    /**
     * 是否只查询默认变体价格
     */
    public bool $useDefaultVariant = true;

    /**
     * 当前用户（可选，用于自动获取用户等级）
     */
    public ?UserInterface $user = null;
}

