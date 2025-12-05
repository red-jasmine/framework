<?php

namespace RedJasmine\Warehouse\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

/**
 * 仓库与市场/门店关联模型
 *
 * @property int $id
 * @property int $warehouse_id
 * @property string $market
 * @property string $store
 * @property bool $is_active
 * @property bool $is_primary
 * @property Warehouse $warehouse
 */
class WarehouseMarket extends Model implements OperatorInterface
{
    use HasDateTimeFormatter;
    use HasOperator;
    use SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
    ];

    protected $fillable = [
        'warehouse_id',
        'market',
        'store',
        'is_active',
        'is_primary',
    ];

    /**
     * 关联仓库
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    /**
     * 检查是否启用
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * 检查是否为主要市场/门店
     */
    public function isPrimary(): bool
    {
        return $this->is_primary === true;
    }
}

