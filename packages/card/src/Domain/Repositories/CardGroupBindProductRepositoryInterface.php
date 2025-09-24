<?php

namespace RedJasmine\Card\Domain\Repositories;

use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 卡密分组绑定商品仓库接口
 *
 * 提供卡密分组绑定商品实体的读写操作统一接口
 */
interface CardGroupBindProductRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据商品信息查找绑定记录
     */
    public function findByProduct(UserInterface $owner, string $productType, int $productId, int $skuId) : ?CardGroupBindProduct;


}
