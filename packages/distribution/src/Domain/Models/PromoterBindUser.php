<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 推广员绑定用户
 * 绑定日志
 */
class PromoterBindUser extends Model implements OperatorInterface
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;


    protected $fillable = [
        'user_type',
        'user_id', 
        'promoter_id',
        'status',
        'bind_time',
        'protection_time',
        'expiration_time',
        'bind_reason',
        'invitation_code',
        'unbind_reason',
        'unbind_time'
    ];

    protected function casts() : array
    {
        return [
            'status'          => PromoterBindUserStatusEnum::class,
            'bind_time'       => 'datetime',
            'expiration_time' => 'datetime',
            'protection_time' => 'datetime',
            'unbind_time'     => 'datetime'
        ];
    }


    public function promoter() : BelongsTo
    {
        return $this->belongsTo(Promoter::class, 'id', 'promoter_id');
    }
}