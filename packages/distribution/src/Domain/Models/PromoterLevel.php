<?php

namespace RedJasmine\Distribution\Domain\Models;

use RedJasmine\Distribution\Domain\Data\ConditionData;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApprovalMethodEnum;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use Spatie\LaravelData\DataCollection;

/**
 * 分销员等级
 */
class PromoterLevel extends Model implements OperatorInterface
{

    use HasOperator;


    protected function casts() : array
    {
        return [
            'extra'        => 'array',
            'upgrades'     => DataCollection::class.':'.ConditionData::class.',default',
            'keeps'        => DataCollection::class.':'.ConditionData::class.',default',
            'apply_method' => PromoterApplyMethodEnum::class,
            'approval_method' => PromoterApprovalMethodEnum::class,
        ];
    }
}