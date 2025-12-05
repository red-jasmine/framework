<?php

namespace RedJasmine\Logistics\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Logistics\Domain\Models\Enums\Companies\CompanyTypeEnum;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class LogisticsCompany extends Model implements OperatorInterface
{

    use HasOperator;




    protected function casts() : array
    {
        return [
            'type'   => CompanyTypeEnum::class,
            'status' => UniversalStatusEnum::class,
        ];
    }

    protected $fillable = [
        'type',
        'code',
        'name',
        'url',
        'logo',
        'tel'
    ];


}
