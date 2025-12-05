<?php

namespace RedJasmine\Product\Domain\Product\Data;

use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class FreightTemplateData extends Data
{

    #[WithCast(EnumCast::class, ShippingTypeEnum::class)]
    public ShippingTypeEnum $shippingType;

    /**
     * 运费支付人
     * @var FreightPayerEnum
     */
    #[WithCast(EnumCast::class, FreightPayerEnum::class)]
    public FreightPayerEnum $freightPayer = FreightPayerEnum::SELLER;


    /**
     * 运费模板id
     *
     * 当前运费承担方为 买家时、运费模板必须填写
     * @var ?string
     */
    public ?string $freightTemplateId = null;


}