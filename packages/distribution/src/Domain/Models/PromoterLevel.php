<?php

namespace RedJasmine\Distribution\Domain\Models;

use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

/**
 * 分销员等级
 */
class PromoterLevel extends Model implements OperatorInterface
{

    use HasOperator;
}