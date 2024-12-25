<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum NotifyStatusEnum: string
{
    use EnumsHelper;

    case WAIT = 'wait';
    case SUCCESS = 'success';
    case TRYING = 'trying';
    case FAIL = 'fail';


    public static function labels() : array
    {
        return [
            self::WAIT->value    => '等待中',
            self::TRYING->value  => '重试中',
            self::SUCCESS->value => '成功',
            self::FAIL->value    => '失败',
        ];
    }


}
