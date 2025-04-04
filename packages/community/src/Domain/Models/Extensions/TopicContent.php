<?php

namespace RedJasmine\Community\Domain\Models\Extensions;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Community\Domain\Models\Topic;


class TopicContent extends Model
{


    use SoftDeletes;

    protected $fillable = [
        'topic_id'
    ];


    public function topic() : BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

}
