<?php

namespace RedJasmine\Support\Http\Controllers;


use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\ClientInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Helpers\ClientObjectBuilder;
use RedJasmine\Support\Helpers\UserObjectBuilder;

trait UserOwnerTools
{
    /**
     * 当前所属人
     * @return UserInterface|null
     */
    public function getOwner() : ?UserInterface
    {
        if ($this->getUser() instanceof BelongsToOwnerInterface) {
            request()->user()->owner();
        }

        return $this->getUser();
    }

    /**
     * 当前所属人
     * @return null|User
     */
    public function getUser() : ?UserInterface
    {
        return request()->user();
    }

    /**
     * 获取游客信息
     * @return UserInterface|null
     */
    public function getGuest() : ?UserInterface
    {
        // TODO  根据 设备ID、 ip 等信息
        $guest = [
            'type'     => 'guest',
            'uid'      => 0,
            'nickname' => '游客',
            'avatar'   => '',
        ];
        return new UserObjectBuilder($guest);

    }


    /**
     * 客户端信息
     * @return ClientInterface
     */
    public function getClient() : ClientInterface
    {
        return new ClientObjectBuilder(request());
    }


}
