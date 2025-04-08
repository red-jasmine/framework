<?php

namespace RedJasmine\Community\Domain\Models\Extensions;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;


class TopicExtension extends Model
{


    use SoftDeletes;

    protected $fillable = [
        'id'
    ];

    protected function casts() : array
    {
        return [
            'content_type' => ContentTypeEnum::class,
        ];
    }


    public function getTable() : string
    {
        return 'topics_extension';
    }

    public function topic() : BelongsTo
    {
        return $this->belongsTo(Topic::class, 'id', 'id');
    }

}
