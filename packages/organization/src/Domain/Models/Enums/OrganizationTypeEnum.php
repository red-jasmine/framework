<?php

namespace RedJasmine\Organization\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum OrganizationTypeEnum: string
{
    use EnumsHelper;

    case COMPANY = 'company';
    case GOVERNMENT = 'government';
    case SCHOOL = 'school';
    case NON_PROFIT = 'non_profit';
    case ASSOCIATION = 'association';
    case OTHER = 'other';

    public static function labels(): array
    {
        return [
            self::COMPANY->value => '公司',
            self::GOVERNMENT->value => '政府机构',
            self::SCHOOL->value => '学校',
            self::NON_PROFIT->value => '非营利组织',
            self::ASSOCIATION->value => '协会',
            self::OTHER->value => '其他',
        ];
    }

    public static function colors(): array
    {
        return [
            self::COMPANY->value => 'primary',
            self::GOVERNMENT->value => 'success',
            self::SCHOOL->value => 'info',
            self::NON_PROFIT->value => 'warning',
            self::ASSOCIATION->value => 'secondary',
            self::OTHER->value => 'gray',
        ];
    }

    public static function icons(): array
    {
        return [
            self::COMPANY->value => 'heroicon-o-building-office-2',
            self::GOVERNMENT->value => 'heroicon-o-building-library',
            self::SCHOOL->value => 'heroicon-o-academic-cap',
            self::NON_PROFIT->value => 'heroicon-o-heart',
            self::ASSOCIATION->value => 'heroicon-o-user-group',
            self::OTHER->value => 'heroicon-o-question-mark-circle',
        ];
    }
}
