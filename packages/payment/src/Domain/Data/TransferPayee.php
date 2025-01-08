<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Data\Data;

/**
 * TransferPayee 类用于表示转账收款方的信息
 */
class TransferPayee extends Data
{

    /**
     * 收款方身份类型
     * @var string
     */
    public string $identityType;

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

    /**
     * 收款方证件类型（可选）
     * @var string|null
     */
    public ?string $certType;

    /**
     * 收款方证件号码（可选）
     * @var string|null
     */
    public ?string $certNo;

}
