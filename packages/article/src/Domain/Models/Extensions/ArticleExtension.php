<?php

namespace RedJasmine\Article\Domain\Models\Extensions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;

class ArticleExtension extends Model
{

    public $incrementing = false;

    use SoftDeletes;

    public function getTable() : string
    {
        return 'articles_extension';
    }


    protected $fillable = [
        'id'
    ];
    protected function casts() : array
    {
        return [
            'content_type' => ContentTypeEnum::class,
        ];
    }

    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class, 'id', 'id');
    }


}
