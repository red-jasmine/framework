<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\User\Domain\Enums\UserTagStatusEnum;

class UserTag extends Model implements OperatorInterface
{

    use HasOperator;

    protected  $withOperatorNickname = true;
    protected function casts() : array
    {
        return [
            'extra'  => 'json',
            'status' => UserTagStatusEnum::class
        ];
    }


    public function category() : BelongsTo
    {
        return $this->belongsTo(UserTagCategory::class, 'category_id', 'id');
    }
}
