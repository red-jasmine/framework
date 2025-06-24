<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Application\Services;

use RedJasmine\Invitation\Application\Data\InvitationCodeCreateCommand;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter;
use RedJasmine\Invitation\Domain\Models\ValueObjects\InvitationTag;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Domain\ReadRepositories\InvitationCodeReadRepositoryInterface;
use RedJasmine\Invitation\Domain\Transformers\InvitationCodeTransformer;
use RedJasmine\Invitation\Infrastructure\Services\InvitationCodeGenerator;
use RedJasmine\Invitation\Infrastructure\Services\InvitationLinkGenerator;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 邀请码应用服务
 */
final class InvitationCodeApplicationService extends ApplicationService
{
    public function __construct(
        public InvitationCodeRepositoryInterface $repository,
        public InvitationCodeReadRepositoryInterface $readRepository,
        public InvitationCodeTransformer $transformer,
        public InvitationCodeGenerator $codeGenerator,
        public InvitationLinkGenerator $linkGenerator,
    ) {
    }

    protected static string $modelClass = InvitationCode::class;

    /**
     * 处理器宏方法配置
     * @see \RedJasmine\Invitation\Application\Commands\InvitationCodeCreateCommandHandler::handle()
     * @method InvitationCode create(InvitationCodeCreateCommand $command)
     * @see \RedJasmine\Invitation\Application\Commands\InvitationCodeUseCommandHandler::handle()
     * @method InvitationUsageLog useInvitationCode(InvitationCodeUseCommand $command)
     * @see \RedJasmine\Invitation\Application\Queries\InvitationCodePaginateQueryHandler::handle()
     * @method LengthAwarePaginator|Paginator paginateInvitationCodes(InvitationCodePaginateQuery $query)
     */
    protected static array $macros = [
        'create' => \RedJasmine\Invitation\Application\Commands\InvitationCodeCreateCommandHandler::class,
        'useInvitationCode' => \RedJasmine\Invitation\Application\Commands\InvitationCodeUseCommandHandler::class,
        'paginateInvitationCodes' => \RedJasmine\Invitation\Application\Queries\InvitationCodePaginateQueryHandler::class,
    ];

    /**
     * 创建邀请码
     */
    public function create(InvitationCodeCreateCommand $command): InvitationCode
    {
        // 验证命令数据
        // $command->validate();

        // 生成或验证邀请码
        $code = $this->generateOrValidateCode($command);

        // 创建邀请人值对象
        $inviter = new Inviter(
            type: $command->inviterType,
            id: $command->inviterId,
            name: $command->inviterName
        );

        // 处理标签
        $tags = $this->processTags($command->tags);

        // 处理过期时间
        $expiresAt = $command->expiresAt ? new \DateTime($command->expiresAt) : null;

        // 创建邀请码聚合根
        $invitationCode = InvitationCode::create(
            code: $code,
            inviter: $inviter,
            title: $command->title,
            description: $command->description,
            slogan: $command->slogan,
            generateType: $command->generateType,
            maxUsage: $command->maxUsage,
            expiresAt: $expiresAt,
            tags: $tags,
            extraData: $command->extraData
        );

        // 保存到仓库
        $this->repository->save($invitationCode);

        // 创建去向配置
        if (!empty($command->destinations)) {
            $this->createDestinations($invitationCode, $command->destinations);
        }

        return $invitationCode;
    }

    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode
    {
        return $this->repository->findByCode($code);
    }

    /**
     * 使用邀请码
     */
    public function useCode(string $code): InvitationCode
    {
        $invitationCode = $this->repository->findByCode($code);
        
        if (!$invitationCode) {
            throw new \DomainException('邀请码不存在');
        }

        $invitationCode->use();
        $this->repository->save($invitationCode);

        return $invitationCode;
    }

    /**
     * 禁用邀请码
     */
    public function disable(int $id): void
    {
        $invitationCode = $this->repository->findById($id);
        
        if (!$invitationCode) {
            throw new \DomainException('邀请码不存在');
        }

        $invitationCode->disable();
        $this->repository->save($invitationCode);
    }

    /**
     * 启用邀请码
     */
    public function enable(int $id): void
    {
        $invitationCode = $this->repository->findById($id);
        
        if (!$invitationCode) {
            throw new \DomainException('邀请码不存在');
        }

        $invitationCode->enable();
        $this->repository->save($invitationCode);
    }

    /**
     * 生成邀请链接
     */
    public function generateLink(string $code, string $platform = 'web', array $parameters = []): string
    {
        $invitationCode = $this->repository->findByCode($code);
        
        if (!$invitationCode) {
            throw new \DomainException('邀请码不存在');
        }

        return $this->linkGenerator->generate($invitationCode, $platform, $parameters);
    }

    /**
     * 分页查询
     */
    public function paginate(array $filters = [], int $page = 1, int $pageSize = 20): array
    {
        return $this->repository->paginateInvitationCodes($filters, $page, $pageSize);
    }

    /**
     * 生成或验证邀请码
     */
    protected function generateOrValidateCode(InvitationCodeCreateCommand $command): string
    {
        if ($command->generateType->value === 'custom') {
            // 验证自定义邀请码
            if ($this->repository->existsByCode($command->code)) {
                throw new \DomainException('邀请码已存在');
            }
            return $command->code;
        }

        // 系统生成邀请码
        return $this->codeGenerator->generate();
    }

    /**
     * 处理标签
     */
    protected function processTags(array $tags): array
    {
        return array_map(function ($tag) {
            if (is_array($tag)) {
                return InvitationTag::fromArray($tag);
            }
            return $tag;
        }, $tags);
    }

    /**
     * 创建去向配置
     */
    protected function createDestinations(InvitationCode $invitationCode, array $destinations): void
    {
        // 这里应该调用去向管理服务来创建配置
        // 为了简化，暂时跳过具体实现
    }
} 