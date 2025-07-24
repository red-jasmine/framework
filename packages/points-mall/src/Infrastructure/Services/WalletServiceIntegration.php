<?php

namespace RedJasmine\PointsMall\Infrastructure\Services;

use RedJasmine\PointsMall\Domain\Contracts\WalletServiceInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Wallet\Application\Services\Wallet\Queries\FindByOwnerTypeQuery;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;

/**
 * 积分商城钱包服务集成
 * 对接钱包领域的应用服务
 */
class WalletServiceIntegration implements WalletServiceInterface
{
    public const string WALLET_TYPE = 'points';

    public function __construct(
        protected WalletApplicationService $walletApplicationService,
    ) {
    }

    /**
     * 验证用户积分钱包是否存在且有效
     *
     * @param  UserInterface  $user
     *
     * @return bool 钱包是否有效
     */
    public function validateUserWallet(UserInterface $user) : bool
    {

        $query        = new FindByOwnerTypeQuery();
        $query->owner = $user;
        $query->type  = static::WALLET_TYPE;

        $wallet = $this->walletApplicationService->findByOwnerType($query);
    }

    /**
     * 获取用户积分余额
     *
     * @param  UserInterface  $user
     *
     * @return int 用户积分余额
     */
    public function getPointsBalance(UserInterface $user) : int
    {
        // TODO: Implement getPointsBalance() method.
    }

    /**
     * 扣减用户积分
     *
     * @param  UserInterface  $user
     * @param  int  $points  扣减的积分数量
     * @param  array  $metadata  元数据
     *
     * @return bool 是否扣减成功
     */
    public function deductPoints(UserInterface $user, int $points, array $metadata = []) : bool
    {
        // TODO: Implement deductPoints() method.
    }


} 