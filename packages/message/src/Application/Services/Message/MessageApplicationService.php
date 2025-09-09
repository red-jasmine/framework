<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Application\Services\Message;

use RedJasmine\Message\Application\Services\Commands\MessageArchiveCommandHandler;
use RedJasmine\Message\Application\Services\Commands\MessageCreateCommandHandler;
use RedJasmine\Message\Application\Services\Message\Commands\MessageAllMarkAsReadCommandHandler;
use RedJasmine\Message\Application\Services\Message\Commands\MessageMarkAsReadCommand;
use RedJasmine\Message\Application\Services\Message\Commands\MessageMarkAsReadCommandHandler;
use RedJasmine\Message\Application\Services\Message\Queries\MessageStatisticsQuery;
use RedJasmine\Message\Application\Services\Message\Queries\MessageStatisticsQueryHandler;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;
use RedJasmine\Message\Domain\Transformers\MessageTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * 消息应用服务
 * @method markAsRead(MessageMarkAsReadCommand $command)
 * @method allMarkAsRead(MessageMarkAsReadCommand $command)
 * @method statistics(MessageStatisticsQuery $query)
 */
class MessageApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'message.application';
    protected static string $modelClass     = Message::class;

    public function __construct(
        public MessageRepositoryInterface $repository,
        public MessageTransformer $transformer
    ) {
    }

    protected static $macros = [
        'create'        => MessageCreateCommandHandler::class,
        'markAsRead'    => MessageMarkAsReadCommandHandler::class,
        'allMarkAsRead' => MessageAllMarkAsReadCommandHandler::class,
        'statistics'    => MessageStatisticsQueryHandler::class,

    ];

    /**
     * 获取用户未读消息数量
     */
    public function getUnreadCount(UserInterface $owner, ?string $biz = null) : int
    {
        return $this->repository->getUnreadCount($owner, $biz);
    }




}
