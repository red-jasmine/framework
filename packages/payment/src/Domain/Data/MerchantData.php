<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class MerchantData extends Data
{

    public UserInterface $owner;

    public string $name;
    public string $shortName;

    public MerchantTypeEnum $type = MerchantTypeEnum::GENERAL;


    public ?int $isvId = null;


    public MerchantStatusEnum $status = MerchantStatusEnum::ENABLE;


}
