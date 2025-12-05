<?php

namespace RedJasmine\PointsMall\Domain\Contracts;

use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\Support\Domain\Contracts\UserInterface;

interface WalletServiceInterface
{

    /**
     * 验证用户积分钱包是否存在且有效
     *
     * @param  UserInterface  $user
     *
     * @return bool 钱包是否有效
     */
    public function validateUserWallet(UserInterface $user) : bool;

    /**
     * 获取用户积分余额
     *
     * @param  UserInterface  $user
     *
     * @return int 用户积分余额
     */
    public function getPointsBalance(UserInterface $user) : int;


    /**
     * 扣减用户积分
     * @param  PointsExchangeOrder  $exchangeOrder
     *
     * @return bool
     */
    public function deductPoints(PointsExchangeOrder $exchangeOrder) : bool;


} 