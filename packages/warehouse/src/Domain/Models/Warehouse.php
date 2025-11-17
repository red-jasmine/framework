<?php

namespace RedJasmine\Warehouse\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Warehouse\Domain\Models\Enums\WarehouseTypeEnum;

/**
 * 仓库模型
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property WarehouseTypeEnum $warehouse_type
 * @property ?string $address
 * @property ?string $contact_phone
 * @property ?string $contact_person
 * @property bool $is_active
 * @property bool $is_default
 * @property Collection<WarehouseMarket> $markets
 */
class Warehouse extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $casts = [
        'warehouse_type' => WarehouseTypeEnum::class,
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    protected $fillable = [
        'code',
        'name',
        'owner_type',
        'owner_id',
        'warehouse_type',
        'address',
        'contact_phone',
        'contact_person',
        'is_active',
        'is_default',
    ];

    /**
     * 关联市场/门店
     */
    public function markets(): HasMany
    {
        return $this->hasMany(WarehouseMarket::class, 'warehouse_id');
    }

    /**
     * 获取启用的市场/门店关联
     */
    public function activeMarkets(): HasMany
    {
        return $this->markets()->where('is_active', true);
    }

    /**
     * 检查是否为默认仓库
     */
    public function isDefault(): bool
    {
        return $this->is_default === true;
    }

    /**
     * 检查是否启用
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }
}

