<?php

namespace RedJasmine\Support\UI\Http\Controllers;


use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\ClientInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Helpers\ClientObjectBuilder;
use RedJasmine\Support\Http\Controllers\User;

trait UserOwnerTools
{
    /**
     * 当前所属人
     * @return UserInterface|null
     */
    public function getOwner() : ?UserInterface
    {

        if ($this->getUser() instanceof BelongsToOwnerInterface) {
            return $this->getUser()->owner();
        }

        return $this->getUser();
    }

    /**
     * 当前所属人
     * @return null|User
     */
    public function getUser() : ?UserInterface
    {
        $user = request()->user();
        if ($user) {
            if ($user instanceof UserInterface) {
                return $user;
            }

            return UserData::from(['id' => $user->getKey(), 'type' => get_class($user)]);
        }
        return $user;
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
            'id'       => 0,
            'nickname' => '游客',
            'avatar'   => '',
        ];
        return UserData::from($guest);

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
