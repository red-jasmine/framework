<?php

namespace RedJasmine\Article\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Acticle extends Model
{

    public $incrementing = false;

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;


    public function tags() : HasMany
    {
        return $this->hasMany();
    }


    public function category() : BelongsTo
    {
        return $this->belongsTo();
    }
}
