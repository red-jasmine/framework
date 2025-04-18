<?php

namespace RedJasmine\Region\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class Country extends Model
{
    use HasDateTimeFormatter;


    protected $primaryKey = 'code';

    public $incrementing = false;


    protected function casts() : array
    {
        return [
            'timezones'    => 'array',
            'translations' => 'array'
        ];
    }
}