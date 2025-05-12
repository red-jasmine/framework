<?php

namespace RedJasmine\User\Domain\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class UserTagPivot extends Pivot
{
    use HasDateTimeFormatter;


    public function userTag() : BelongsTo
    {
        return $this->belongsTo(UserTag::class, 'user_tag_id', 'id');
    }
}