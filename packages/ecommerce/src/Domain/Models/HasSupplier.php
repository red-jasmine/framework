<?php

namespace RedJasmine\Ecommerce\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\UserData;

/**
 * 供应商
 */
trait HasSupplier
{

    protected string $supplierColumn = 'supplier';


    public function getSupplierColumnTypeName() : string
    {
        return $this->supplierColumn . '_type';
    }

    public function getSupplierColumnIdName() : string
    {
        return $this->supplierColumn . '_id';
    }

    public function scopeOnlySupplier(Builder $query, UserInterface $supplier) : Builder
    {
        return $query->where($this->getSupplierColumnTypeName(), $supplier->getType())
                     ->where($this->getSupplierColumnIdName(), $supplier->getID());

    }


    public function supplier() : Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (blank($attributes[$this->getSupplierColumnTypeName()] ?? null)) {
                    return null;
                }
                return UserData::from([ 'type' => $attributes[$this->getSupplierColumnTypeName()], 'id' => $attributes[$this->getSupplierColumnIdName()], ]);
            },
            set: fn(?UserInterface $user) => [
                $this->getSupplierColumnTypeName() => $user?->getType(),
                $this->getSupplierColumnIdName()   => $user?->getID()
            ]

        );
    }


}
