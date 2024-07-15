<?php

namespace RedJasmine\Card\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Card\Domain\Enums\CardStatus;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

class Card extends Model
{

    use HasOwner;

    use HasOperator;

    use SoftDeletes;

    protected function casts() : array
    {
        return [
            'status'  => CardStatus::class,
            'is_loop' => 'boolean',
        ];
    }
}
