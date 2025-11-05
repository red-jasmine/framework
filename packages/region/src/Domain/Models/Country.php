<?php

namespace RedJasmine\Region\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

/**
 * 国家地区模型
 *
 * @property string $code 国家代码 ISO 3166-1 alpha-2
 * @property string $iso_alpha_3 国家代码 ISO 3166-1 alpha-3
 * @property string $name 名称
 * @property string|null $region 大区
 * @property string $currency 货币代码
 */
class Country extends Model
{
    use HasDateTimeFormatter;


    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';


    protected function casts() : array
    {
        return [
            'timezones'    => 'array',
            'translations' => 'array'
        ];
    }

    /**
     * 所属的行政区划
     */
    public function regions()
    {
        return $this->hasMany(Region::class, 'country_code', 'code');
    }
}
