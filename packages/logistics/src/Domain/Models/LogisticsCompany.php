<?php

namespace RedJasmine\Logistics\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class LogisticsCompany extends Model
{

    use HasOperator;

    protected $fillable = [
        'code',
        'name',
        'url',
        'logo',
        'letter',
        'tel'
    ];


}
