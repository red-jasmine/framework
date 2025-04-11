<?php

namespace RedJasmine\User\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\User\Domain\Enums\UserGenderEnum;
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

}