<?php

namespace RedJasmine\Support\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class BaseCategoryTranslationModel extends Model implements OperatorInterface
{
    use SoftDeletes;

    use HasSnowflakeId;

    public $uniqueShortId = true;
    public $incrementing  = false;

    use HasDateTimeFormatter;

    use HasOperator;

    use SoftDeletes;


    protected $fillable = [
        'locale_id',
        'locale',
        'name',
        'cluster',
        'description',
        'translation_status',
        'translated_at',
        'reviewed_at',
    ];

}