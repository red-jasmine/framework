<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use RedJasmine\Payment\Domain\Data\TransferCreateData;

/**
 * 转账创建命令
 */
class TransferCreateCommand extends TransferCreateData
{

    /**
     * 是否自定执行
     * @var bool
     */
    public bool $isAutoExecute = true;


}
