<?php

namespace RedJasmine\Article\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Article\Domain\Models\Enums\ArticleStatusEnum;
use RedJasmine\Article\Domain\Models\Extensions\ArticleContent;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Article extends Model implements OwnerInterface, OperatorInterface
{

    protected $fillable = [];

    use HasOperator;

    use HasOwner;

    public $incrementing = false;

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::saving(callback: function (Article $article) {
            if ($article->relationLoaded('tags')) {
                if ($article->tags?->count() > 0) {
                    if (!is_array($article->tags->first())) {
                        $article->tags()->sync($article->tags);
                    } else {
                        $article->tags()->sync($article->tags->pluck('id')->toArray());
                    }

                } else {
                    $article->tags()->sync([]);
                }
                $article->load('tags');
            }
        });
    }

    public function newInstance($attributes = [], $exists = false) : Article
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->status = ArticleStatusEnum::DRAFT;
            $instance->setUniqueIds();
            $instance->setRelation('content', ArticleContent::make(['article_id' => $instance->id]));
            $instance->setRelation('tags', Collection::make([]));
        }
        return $instance;
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

    public function content() : HasOne
    {
        return $this->hasOne(ArticleContent::class, 'article_id', 'id');
    }
}
