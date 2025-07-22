<?php

namespace RedJasmine\PointsMall\Infrastructure\Services;

use RedJasmine\Wallet\Application\Services\WalletApplicationService;
use RedJasmine\PointsMall\Domain\Contracts\WalletServiceInterface;

/**
 * 积分商城钱包服务集成
 * 对接钱包领域的应用服务
 */
class WalletServiceIntegration implements WalletServiceInterface
{
    public function __construct(
        protected WalletApplicationService $walletApplicationService,
    ) {
    }

    /**
     * 获取积分余额
     *
     * @param string $ownerType
     * @param string $ownerId
     * @return int
     */
    public function getPointsBalance(string $ownerType, string $ownerId): int
    {
        try {
            return $this->walletApplicationService->getPointsBalance($ownerType, $ownerId);
        } catch (\Throwable $throwable) {
            return 0;
        }
    }

    /**
     * 扣除积分
     *
     * @param string $ownerType
     * @param string $ownerId
     * @param int $points
     * @param array $metadata
     * @return bool
     */
    public function deductPoints(string $ownerType, string $ownerId, int $points, array $metadata = []): bool
    {
        try {
            return $this->walletApplicationService->deductPoints($ownerType, $ownerId, $points, $metadata);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * 验证积分余额
     *
     * @param string $ownerType
     * @param string $ownerId
     * @param int $requiredPoints
     * @return bool
     */
    public function validatePointsBalance(string $ownerType, string $ownerId, int $requiredPoints): bool
    {
        try {
            return $this->walletApplicationService->validatePointsBalance($ownerType, $ownerId, $requiredPoints);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * 创建积分交易记录
     *
     * @param string $ownerType
     * @param string $ownerId
     * @param int $points
     * @param string $type
     * @param array $metadata
     * @return string|null
     */
    public function createPointsTransaction(string $ownerType, string $ownerId, int $points, string $type, array $metadata = []): ?string
    {
        try {
            return $this->walletApplicationService->createPointsTransaction($ownerType, $ownerId, $points, $type, $metadata);
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    /**
     * 获取积分交易记录
     *
     * @param string $ownerType
     * @param string $ownerId
     * @param int $limit
     * @return array
     */
    public function getPointsTransactions(string $ownerType, string $ownerId, int $limit = 20): array
    {
        try {
            return $this->walletApplicationService->getPointsTransactions($ownerType, $ownerId, $limit);
        } catch (\Throwable $throwable) {
            return [];
        }
    }

    /**
     * 验证钱包状态
     *
     * @param string $ownerType
     * @param string $ownerId
     * @return bool
     */
    public function validateWalletStatus(string $ownerType, string $ownerId): bool
    {
        try {
            return $this->walletApplicationService->validateWalletStatus($ownerType, $ownerId);
        } catch (\Throwable $throwable) {
            return false;
        }
    }
} 