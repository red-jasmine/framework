<?php

namespace RedJasmine\PointsMall\Infrastructure\Services;

use Cknow\Money\Money;
use RedJasmine\PointsMall\Domain\Contracts\WalletServiceInterface;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\Wallet\Queries\FindByOwnerTypeQuery;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;

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

        return $wallet->isAvailable();
    }

    private function getWallet(UserInterface $user) : Wallet
    {
        $query        = new FindByOwnerTypeQuery();
        $query->owner = $user;
        $query->type  = static::WALLET_TYPE;

        return $this->walletApplicationService->findByOwnerType($query);
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

        $wallet = $this->getWallet($user);
        return (int) $wallet->balance->formatByDecimal();

    }

    /**
     * 扣减用户积分
     *
     * @param  PointsExchangeOrder  $exchangeOrder
     *
     * @return bool
     */
    public function deductPoints(PointsExchangeOrder $exchangeOrder) : bool
    {
        $point = $exchangeOrder->point;

        $wallet = $this->getWallet($exchangeOrder->user);

        $command = new WalletTransactionCommand();
        $command->setKey($wallet->id);
        $command->direction       = AmountDirectionEnum::EXPENSE;
        $command->transactionType = TransactionTypeEnum::PAYMENT;
        $command->outTradeNo      = $exchangeOrder->getUniqueNo();

        $command->amount = Money::parse($point, $wallet->balance->getCurrency());

        $this->walletApplicationService->transaction($command);
        return true;
    }


} 