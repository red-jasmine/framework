<?php

namespace RedJasmine\Order\Domain\Data;

use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyContentTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;

class CardKeyData extends Data
{
    public OrderCardKeyContentTypeEnum $contentType = OrderCardKeyContentTypeEnum::TEXT;

    /**
     * 内容
     * @var string
     */
    public string $content;


    /**
     * 数量
     * @var int
     */
    public int $quantity = 1;


    /**
     * @var string|null
     */
    public ?string $sourceType = null;

    /**
     * @var string|null
     */
    public ?string $sourceId = null;


    public OrderCardKeyStatusEnum $status = OrderCardKeyStatusEnum::SHIPPED;

}
