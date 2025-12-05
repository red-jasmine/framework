<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use RedJasmine\Support\Foundation\Data\Data;

/**
 * 转账执行命令
 */
class TransferExecutingCommand extends Data
{

    public string $transferNo;

}
