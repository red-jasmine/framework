<?php

namespace RedJasmine\FilamentCore\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum JsonSchemaTypeEnum: string
{
    use EnumsHelper;

    case STRING = 'string';
    case NUMBER = 'number';
    case INTEGER = 'integer';
    case BOOLEAN = 'boolean';
    case OBJECT = 'object';
    case ARRAY = 'array';
    case NULL = 'null';

    public static function labels(): array
    {
        return [
            self::STRING->value => '字符串',
            self::NUMBER->value => '数字',
            self::INTEGER->value => '整数',
            self::BOOLEAN->value => '布尔值',
            self::OBJECT->value => '对象',
            self::ARRAY->value => '数组',
            self::NULL->value => '空值',
        ];
    }

    public static function icons(): array
    {
        return [
            self::STRING->value => 'heroicon-o-document-text',
            self::NUMBER->value => 'heroicon-o-calculator',
            self::INTEGER->value => 'heroicon-o-hashtag',
            self::BOOLEAN->value => 'heroicon-o-check-circle',
            self::OBJECT->value => 'heroicon-o-cube',
            self::ARRAY->value => 'heroicon-o-list-bullet',
            self::NULL->value => 'heroicon-o-x-circle',
        ];
    }
}

