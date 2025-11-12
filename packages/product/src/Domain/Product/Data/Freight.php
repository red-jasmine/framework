<?php

namespace RedJasmine\Product\Domain\Product\Data;

use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Support\Data\Data;

class Freight extends Data
{

    /**
     * 运费支付人
     * @var FreightPayerEnum
     */
    public FreightPayerEnum $freightPayer = FreightPayerEnum::SELLER;


    /**
     * 运费模板id
     *
     * 当前运费承担方为 买家时、运费模板必须填写
     * @var int|null
     */
    public ?int $freightTemplateId = null;

}