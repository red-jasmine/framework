<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

/**
 * 分销员分组
 */
class PromoterGroup extends Model implements OperatorInterface
{
    use HasOperator;
}