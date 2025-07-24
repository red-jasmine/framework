<?php

namespace RedJasmine\PointsMall\Domain\Contracts;

use RedJasmine\Support\Contracts\UserInterface;

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
     *
     * @param  UserInterface  $user
     * @param  int  $points  扣减的积分数量
     * @param  array  $metadata  元数据
     *
     * @return bool 是否扣减成功
     */
    public function deductPoints(UserInterface $user, int $points, array $metadata = []) : bool;


} 