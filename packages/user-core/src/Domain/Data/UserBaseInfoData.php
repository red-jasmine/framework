<?php

namespace RedJasmine\UserCore\Domain\Data;

use RedJasmine\Support\Foundation\Data\Data;
use RedJasmine\UserCore\Domain\Enums\UserGenderEnum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class UserBaseInfoData extends Data
{
    public ?string         $nickname;
    #[WithCast(EnumCast::class, UserGenderEnum::class)]
    public ?UserGenderEnum $gender;
    public ?string         $birthday;
    public ?string         $avatar;
    #[Max(50)]
    public ?string         $biography;

    // 地区
    public ?string $country;
    public ?string $province;
    public ?string $city;
    public ?string $district;

    // 学校
    public ?string $school;
}