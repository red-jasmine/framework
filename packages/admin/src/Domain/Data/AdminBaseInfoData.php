<?php

namespace RedJasmine\Admin\Domain\Data;

use RedJasmine\Admin\Domain\Models\Enums\AdminGenderEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class AdminBaseInfoData extends Data
{
    public ?string          $nickname;
    #[WithCast(EnumCast::class, AdminGenderEnum::class)]
    public ?AdminGenderEnum $gender;
    public ?string          $birthday;
    public ?string          $avatar;
    #[Max(50)]
    public ?string          $biography;

    // 地区
    public ?string $country;
    public ?string $province;
    public ?string $city;
    public ?string $district;

    // 学校
    public ?string $school;
}