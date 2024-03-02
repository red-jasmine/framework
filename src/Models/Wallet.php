<?php

namespace RedJasmine\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOwner;
use RedJasmine\Support\Traits\Models\WithDTO;

class Wallet extends Model
{

    use WithDTO;

    use HasOwner;

    use HasDateTimeFormatter;

    public $incrementing = false;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'type',
        'id'
    ];
    protected $casts    = [
        'status' => WalletStatuaEnum::class
    ];

    public function transactions() : HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id', 'id');
    }
}
