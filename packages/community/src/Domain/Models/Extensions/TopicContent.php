<?php

namespace RedJasmine\Community\Domain\Models\Extensions;


use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Order\Domain\Models\Model;

class TopicContent extends Model
{


    use SoftDeletes;

    protected $fillable = [
        'topic_id'
    ];


    public function article() : BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

}
