<?php

namespace RedJasmine\Support\UI\Http\Controllers;

use RedJasmine\Support\Data\UserData;
use function request;

/**
 * 只作用于 拥有者权限的接口
 * @property bool $injectionOwner
 */
trait HasInjectionOwner
{

    protected function injectionOwnerRequest()
    {
        if ($this->hasOnlyOwner()) {
            request()->offsetSet($this->getOwnerKey(), UserData::fromUserInterface($this->getOwner()));
        }
    }

    protected function hasOnlyOwner() : bool
    {
        return property_exists($this, 'injectionOwner') ? $this->injectionOwner : true;
    }

    protected function getOwnerKey() : string
    {
        return property_exists($this, 'ownerKey') ? $this->ownerKey : 'owner';
    }


}