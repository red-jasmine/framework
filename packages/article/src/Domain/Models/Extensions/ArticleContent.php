<?php

namespace RedJasmine\Article\Domain\Models\Extensions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Article\Domain\Models\Article;

class ArticleContent extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'article_id'
    ];


    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }


}
