<?php

namespace RedJasmine\Warehouse\Domain\Data;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use RedJasmine\Warehouse\Domain\Models\Enums\WarehouseTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class WarehouseData extends Data
{
    /**
     * 仓库编码
     */
    public string $code;

    /**
     * 仓库名称
     */
    public string $name;

    /**
     * 所属者
     */
    public UserInterface $owner;

    /**
     * 仓库类型
     */
    #[WithCast(EnumCast::class, WarehouseTypeEnum::class)]
    public WarehouseTypeEnum $warehouseType = WarehouseTypeEnum::WAREHOUSE;

    /**
     * 地址
     */
    public ?string $address = null;

    /**
     * 联系电话
     */
    public ?string $contactPhone = null;

    /**
     * 联系人
     */
    public ?string $contactPerson = null;

    /**
     * 是否启用
     */
    public bool $isActive = true;

    /**
     * 是否默认仓库
     */
    public bool $isDefault = false;

    /**
     * 仓库市场/门店关联列表
     *
     * @var WarehouseMarketData[]
     */
    public array $markets = [];

    public static function rules(ValidationContext $context): array
    {
        return [
            'code' => ['required', 'string', 'max:64'],
            'name' => ['required', 'string', 'max:255'],
            'warehouse_type' => ['sometimes', 'string'],
            'address' => ['sometimes', 'nullable', 'string'],
            'contact_phone' => ['sometimes', 'nullable', 'string', 'max:32'],
            'contact_person' => ['sometimes', 'nullable', 'string', 'max:64'],
            'is_active' => ['sometimes', 'boolean'],
            'is_default' => ['sometimes', 'boolean'],
            'markets' => ['sometimes', 'array'],
            'markets.*.market' => ['required', 'string', 'max:32'],
            'markets.*.store' => ['required', 'string', 'max:32'],
            'markets.*.is_active' => ['sometimes', 'boolean'],
            'markets.*.is_primary' => ['sometimes', 'boolean'],
        ];
    }
}

