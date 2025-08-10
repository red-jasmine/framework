<?php

namespace RedJasmine\Article\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use RedJasmine\Article\Domain\Models\Enums\ArticleStatusEnum;
use RedJasmine\Article\Domain\Models\Extensions\ArticleExtension;
use RedJasmine\Article\Exceptions\ArticleException;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasApproval;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasTags;

class Article extends Model implements OwnerInterface, OperatorInterface
{

    protected $fillable = [];

    use HasOperator;

    use HasOwner;

    public $incrementing = false;

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasTags;

    use HasApproval;


    public function newInstance($attributes = [], $exists = false) : Article
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->status = ArticleStatusEnum::DRAFT;
            $instance->setUniqueIds();
            $instance->setRelation('extension', ArticleExtension::make(['id' => $instance->id]));
            $instance->setRelation('tags', Collection::make([]));
        }
        return $instance;
    }

    protected function casts() : array
    {
        return [
            'is_top'          => 'boolean',
            'is_show'         => 'boolean',
            'publish_time'    => 'datetime',
            'status'          => ArticleStatusEnum::class,
            'approval_status' => ApprovalStatusEnum::class,
        ];
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(
            ArticleTag::class,
            'article_tag_pivots',
            'article_id',
            'article_tag_id'
        )->withTimestamps();
    }


    public function category() : BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id');
    }


    public function extension() : HasOne
    {
        return $this->hasOne(ArticleExtension::class, 'id', 'id');
    }


    public function scopeShow(Builder $builder) : Builder
    {
        $builder->where('status', ArticleStatusEnum::PUBLISHED)
                ->where('is_show', true);

        return $builder;
    }


    public function canPublish() : bool
    {
        if ($this->approval_status !== ApprovalStatusEnum::PASS) {
            return false;
        }

        if ($this->status === ArticleStatusEnum::PUBLISHED) {
            return false;
        }

        return true;

    }

    /**
     * @return void
     * @throws ArticleException
     */
    public function publish() : void
    {
        if (!$this->canPublish()) {
            throw new ArticleException();
        }
        $this->status       = ArticleStatusEnum::PUBLISHED;
        $this->publish_time = Carbon::now();
    }
}

