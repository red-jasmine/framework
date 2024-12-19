<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum NotifyStatusEnum: string
{
    use EnumsHelper;

    // 异步通知状态枚举
    case SUCCESS = 'success';
    case FAIL = 'fail';
    case TRYING = 'Trying';


    public static function labels() : array
    {
        return [
            self::SUCCESS->value => '成功',
            self::TRYING->value  => '重试中',
            self::FAIL->value    => '失败',
        ];
    }


}
