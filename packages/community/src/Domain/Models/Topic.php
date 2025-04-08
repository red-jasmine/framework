<?php

namespace RedJasmine\Community\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Community\Domain\Models\Enums\TopicStatusEnum;
use RedJasmine\Community\Domain\Models\Extensions\TopicExtension;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasApproval;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasTags;

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

    use HasApproval;

    use HasTags;


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


    public function extension() : HasOne
    {
        return $this->hasOne(TopicExtension::class, 'id', 'id');
    }


    public function newInstance($attributes = [], $exists = false) : Topic
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->status = TopicStatusEnum::DRAFT;
            $instance->setUniqueIds();
            $instance->setRelation('extension', TopicExtension::make(['id' => $instance->id]));
        }
        return $instance;
    }


    public function scopeShow(Builder $builder) : Builder
    {
        $builder->where('status', TopicStatusEnum::PUBLISH);

        return $builder;
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(
            TopicTag::class,
            'topic_tag_pivots',
            'topic_id',
            'topic_tag_id'
        )->withTimestamps();
    }


}
