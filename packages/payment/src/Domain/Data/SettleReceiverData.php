<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SettleRelationTypeEnum;
use RedJasmine\Support\Data\Data;

class SettleReceiverData extends Data
{
    public int $systemMerchantId;

    public SettleRelationTypeEnum $relationType;

    public string $name;

    public array $accounts;

    public ?CertTypeEnum $certType;
    public ?string       $certNo;


}
