<?php

namespace RedJasmine\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOwner;

class Wallet extends Model
{
    use HasDateTimeFormatter;

    use HasOwner;

    public $incrementing = false;

    protected $casts = [
        'status' => WalletStatuaEnum::class
    ];
}
