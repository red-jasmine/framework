<?php

namespace RedJasmine\Product\Domain\Service\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Data\BaseCategoryTranslationData;
use RedJasmine\Support\Domain\Models\BaseCategoryTranslationModel;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 商品服务翻译模型
 *
 * @property int $id
 * @property int $locale_id
 * @property string $locale
 * @property string $name
 * @property string|null $slogan
 * @property string|null $description
 * @property TranslationStatusEnum $translation_status
 * @property Carbon|null $translated_at
 * @property Carbon|null $reviewed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class ProductServiceTranslation extends BaseCategoryTranslationModel implements OperatorInterface
{
    
    /**
     * 关联到商品服务
     */
    public function productService() : BelongsTo
    {
        return $this->belongsTo(ProductService::class, 'locale_id');
    }

}

