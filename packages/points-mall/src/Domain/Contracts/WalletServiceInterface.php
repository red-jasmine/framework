<?php

namespace RedJasmine\PointsMall\Domain\Contracts;

interface WalletServiceInterface
{
    /**
     * 获取用户积分余额
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @return int 积分余额
     */
    public function getPointsBalance(string $ownerType, string $ownerId): int;

    /**
     * 扣除积分
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param int $points 扣除的积分数量
     * @param array $metadata 元数据
     * @return bool 是否扣除成功
     */
    public function deductPoints(string $ownerType, string $ownerId, int $points, array $metadata = []): bool;

    /**
     * 验证积分是否足够
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param int $requiredPoints 需要的积分数量
     * @return bool 积分是否足够
     */
    public function validatePointsBalance(string $ownerType, string $ownerId, int $requiredPoints): bool;

    /**
     * 创建积分交易记录
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param int $points 积分数量
     * @param string $type 交易类型
     * @param array $metadata 元数据
     * @return string|null 交易记录ID
     */
    public function createPointsTransaction(string $ownerType, string $ownerId, int $points, string $type, array $metadata = []): ?string;

    /**
     * 获取积分交易记录
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param int $limit 限制数量
     * @return array 交易记录列表
     */
    public function getPointsTransactions(string $ownerType, string $ownerId, int $limit = 20): array;

    /**
     * 验证钱包状态
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @return bool 钱包状态是否正常
     */
    public function validateWalletStatus(string $ownerType, string $ownerId): bool;
} 