<?php

namespace RedJasmine\PointsMall\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PointsProductStatusEnum: string
{
    use EnumsHelper;

    case DRAFT = 'draft';
    case ON_SALE = 'on_sale';
    case OFF_SALE = 'off_sale';
    case SOLD_OUT = 'sold_out';

    public static function labels(): array
    {
        return [
            self::DRAFT->value => '草稿',
            self::ON_SALE->value => '上架销售',
            self::OFF_SALE->value => '下架',
            self::SOLD_OUT->value => '售罄',
        ];
    }

    public static function colors(): array
    {
        return [
            self::DRAFT->value => 'gray',
            self::ON_SALE->value => 'green',
            self::OFF_SALE->value => 'yellow',
            self::SOLD_OUT->value => 'red',
        ];
    }

    public static function icons(): array
    {
        return [
            self::DRAFT->value => 'heroicon-o-document',
            self::ON_SALE->value => 'heroicon-o-check-circle',
            self::OFF_SALE->value => 'heroicon-o-pause-circle',
            self::SOLD_OUT->value => 'heroicon-o-x-circle',
        ];
    }
} 