<?php

namespace RedJasmine\Organization\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class DepartmentManager extends Model
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;

    public $incrementing = false;

    protected $fillable = [];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }
}


