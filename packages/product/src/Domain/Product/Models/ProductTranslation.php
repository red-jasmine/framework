<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;

class ProductTranslation extends Model
{
    use SoftDeletes;

    protected $table = 'product_translations';

    protected $fillable = [
        'product_id',
        'locale',
        'title',              // 来自 products 表
        'slogan',             // 来自 products_extension 表
        'description',        // 来自 products_extension 表（富文本详情）
        'meta_title',         // 来自 products_extension 表
        'meta_keywords',      // 来自 products_extension 表
        'meta_description',   // 来自 products_extension 表
        'translation_status',
        'translated_at',
        'reviewed_at',
    ];

    protected $casts = [
        'translation_status' => TranslationStatusEnum::class,
        'translated_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * 关联商品
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

