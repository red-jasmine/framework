<?php

namespace RedJasmine\Ecommerce\Domain\Form\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum FieldTypeEnum: string
{
    use EnumsHelper;

    case TEXT = 'text';

    case TEXTAREA = 'textarea';

    case SELECT = 'select';

    case RADIO = 'radio';

    case DATE = 'date';

    case DATETIME = 'datetime';

    case TIME = 'time';


    public static function labels() : array
    {
        return [
            self::TEXT->value     => '文本',
            self::TEXTAREA->value => '多行文本',
            self::SELECT->value   => '下拉',
            self::RADIO->value    => '单选',
            self::DATE->value     => '日期',
            self::DATETIME->value => '日期时间',
            self::TIME->value     => '时间',
        ];
    }


}
