<?php

namespace RedJasmine\Shop\Domain\Models;

use RedJasmine\Shop\Domain\Events\ShopCancelEvent;
use RedJasmine\Shop\Domain\Events\ShopLoginEvent;
use RedJasmine\Shop\Domain\Events\ShopRegisteredEvent;
use RedJasmine\User\Domain\Models\User;
use Spatie\Permission\Traits\HasRoles;

class Shop extends User
{
    public static string $tagModelClass   = ShopTag::class;
    public static string $tagTable        = 'shop_tag_pivot';
    public static string $groupModelClass = ShopGroup::class;

    public $uniqueShortId = true;

    use HasRoles;


    protected $dispatchesEvents = [
        'login'    => ShopLoginEvent::class,
        'register' => ShopRegisteredEvent::class,
        'cancel'   => ShopCancelEvent::class,
    ];

    // 店铺管理员
    public function isShopManager() : bool
    {
        return true;
    }

    public function getType() : string
    {
        return 'shop';
    }
} 