<?php

namespace RedJasmine\Wallet\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class WalletConfigData extends Data
{

    /**
     *  钱包类型
     * @var string
     */
    public string $type;

    /**
     * 币种
     * @var string
     */
    public string $currency;

    // 是否支持充值
    public bool $recharge = false;
    // 提现
    public bool $withdrawal = false;


    /**
     * 允许的用户类型
     *
     * @var string[]
     */
    public array $userTypes = [];


    public function isAllowUserType(UserInterface $user) : bool
    {
        return in_array($user->getType(), $this->userTypes);
    }

}