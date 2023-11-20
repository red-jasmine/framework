<?php

namespace RedJasmine\Card\Contracts;

interface ItemInterface
{

    /**
     * 商品类型
     * @return string
     */
    public function type() : string;

    /**
     * 商品ID
     * @return string
     */
    public function id() : string;


    /**
     * 规格ID
     * @return string|null
     */
    public function skuID() : ?string;

}
