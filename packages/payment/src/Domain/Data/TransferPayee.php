<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\IdentityTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * TransferPayee 类用于表示转账收款方的信息
 */
class TransferPayee extends Data
{

    #[WithCast(EnumCast::class, IdentityTypeEnum::class)]
    public IdentityTypeEnum $identityType;

    /**
     * 收款方身份ID
     * @var string
     */
    public string $identityId;

    /**
     * 收款方姓名（可选）
     * @var string|null
     */
    public ?string $name;

    #[WithCast(EnumCast::class, CertTypeEnum::class)]
    public ?CertTypeEnum $certType;

    /**
     * 收款方证件号码（可选）
     * @var string|null
     */
    public ?string $certNo;

}
