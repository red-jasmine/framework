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
            self::PROCESSING->value => __('red-jasmine-support::support.enums.approval_status.processing'),
            self::PASS->value       => __('red-jasmine-support::support.enums.approval_status.pass'),
            self::REJECT->value     => __('red-jasmine-support::support.enums.approval_status.reject'),
            self::REVOKE->value     => __('red-jasmine-support::support.enums.approval_status.revoke'),
        ];
    }

}
