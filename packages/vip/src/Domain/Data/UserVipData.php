<?php

namespace RedJasmine\Vip\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class VipData extends Data
{

    public UserInterface $owner;
    public string        $appID;
    public string        $type;
    public bool          $isForever = false;
    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public Carbon        $endTime;


}