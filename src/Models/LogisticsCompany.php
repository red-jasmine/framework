<?php

namespace RedJasmine\Logistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogisticsCompany extends Model
{
    protected $fillable = [
        'code',
        'name',
        'url',
        'logo',
        'letter',
        'tel'
    ];


}
