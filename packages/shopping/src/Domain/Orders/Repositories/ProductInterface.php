<?php

namespace RedJasmine\Shopping\Domain\Repositories;

/**
 * 商品领域接口
 */
interface ProductInterface
{

    /**
     * 获取商品
     *
     * @param  int  $id
     *
     * @return mixed
     */
    public function find(int $id);
}
