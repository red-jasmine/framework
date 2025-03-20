<?php

namespace RedJasmine\Support\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ApprovalStatusEnum: string
{

    use EnumsHelper;

    case PROCESSING = 'processing';
    case PASS = 'pass';
    case REJECT = 'reject';
    case REVOKE = 'revoke';


    public static function labels() : array
    {

        return [
            self::PROCESSING->value => '审批中',
            self::PASS->value       => '通过',
            self::REJECT->value     => '驳回',
            self::REVOKE->value     => '撤销',
        ];
    }

}
