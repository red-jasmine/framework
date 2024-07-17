<?php

namespace RedJasmine\Card\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

class CardGroup extends Model implements OwnerInterface, OperatorInterface
{

    use HasOwner;

    use HasOperator;


    public $incrementing = false;


    protected $fillable = [
        'name',
        'remarks'
    ];


    public function cards() : HasMany
    {
        return $this->hasMany(Card::class, 'group_id', 'id');
    }
}
