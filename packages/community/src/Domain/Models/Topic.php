<?php

namespace RedJasmine\Community\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Community\Domain\Models\Enums\TopicStatusEnum;
use RedJasmine\Community\Domain\Models\Extensions\TopicContent;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property $id
 * @property $title
 * @property TopicSttatusEnum $status
 * @property $is_top
 * @property $sort
 * @property $category_id
 * @property $approval_status
 * @property boolean $is_best
 * @property $version
 *
 */
class Topic extends Model implements OwnerInterface, OperatorInterface
{

    public $incrementing = false;

    public $uniqueShortId = true;

    use HasSnowflakeId;

    use HasOwner;

    use HasOperator;

    use SoftDeletes;


    protected function casts() : array
    {
        return [
            'status'          => TopicStatusEnum::class,
            'approval_status' => ApprovalStatusEnum::class,
            'is_best'         => 'boolean',
        ];
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(TopicCategory::class, 'category_id', 'id');
    }

    public function content() : HasOne
    {
        return $this->hasOne(TopicContent::class, 'topic_id', 'id');
    }

    public function newInstance($attributes = [], $exists = false) : Topic
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->status = TopicStatusEnum::DRAFT;
            $instance->setUniqueIds();
            $instance->setRelation('content', TopicContent::make(['topic_id' => $instance->id]));
        }
        return $instance;
    }


    public function scopeShow(Builder $builder) : Builder
    {
        $builder->where('status', TopicStatusEnum::PUBLISH);

        return $builder;
    }


}
