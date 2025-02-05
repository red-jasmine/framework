<?php

namespace RedJasmine\User\Domain\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\Validation\Max;

class UserBaseInfoData extends Data
{


    public ?string $nickname;
    public ?string $gender;
    public ?string $birthday;
    public ?string $avatar;
    #[Max(50)]
    public ?string $biography;

}