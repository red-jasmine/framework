<?php

namespace RedJasmine\Project\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProjectType: string
{
    use EnumsHelper;

    case STANDARD = 'standard';
    case TEMPLATE = 'template';
    case TEMPORARY = 'temporary';

    public static function labels(): array
    {
        return [
            self::STANDARD->value => '标准项目',
            self::TEMPLATE->value => '模板项目',
            self::TEMPORARY->value => '临时项目',
        ];
    }

    public static function colors(): array
    {
        return [
            self::STANDARD->value => 'blue',
            self::TEMPLATE->value => 'purple',
            self::TEMPORARY->value => 'orange',
        ];
    }

    public static function icons(): array
    {
        return [
            self::STANDARD->value => 'heroicon-o-folder',
            self::TEMPLATE->value => 'heroicon-o-document-duplicate',
            self::TEMPORARY->value => 'heroicon-o-clock',
        ];
    }
}
