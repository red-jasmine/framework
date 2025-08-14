<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use RedJasmine\Message\Application\Services\Commands\MessageCreateCommand;
use RedJasmine\Message\Application\Services\Commands\MessageMarkAsReadCommand;
use RedJasmine\Message\Application\Services\Commands\MessageSendCommand;
use RedJasmine\Message\Application\Services\MessageApplicationService;
use RedJasmine\Message\Application\Services\Queries\MessageFindQuery;
use RedJasmine\Message\Application\Services\Queries\MessageListQuery;
use RedJasmine\Message\Application\Services\Queries\MessageStatisticsQuery;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageCreateRequest;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageListRequest;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageMarkAsReadRequest;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageSendRequest;
use RedJasmine\Message\UI\Http\User\Api\Resources\MessageResource;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 消息用户端控制器
 */
class MessageController
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = MessageResource::class;
    protected static string $paginateQueryClass = MessageListQuery::class;
    protected static string $modelClass = Message::class;
    protected static string $dataClass = MessageCreateCommand::class;

    public function __construct(
        protected MessageApplicationService $service,
    ) {
        // 设置查询作用域 - 只能访问自己的消息
        $this->service->readRepository->withQuery(function ($query) {
            $query->where('receiver_id', $this->getOwner()->getKey());
        });
    }

    /**
     * 获取消息列表
     */
    public function index(MessageListRequest $request): JsonResponse
    {
        $query = MessageListQuery::from([
            ...$request->validated(),
            'owner' => $this->getOwner(),
            'receiverId' => (string) $this->getOwner()->getKey(),
        ]);

        $messages = $this->service->paginate($query);

        return $this->jsonPaginated($messages, static::$resourceClass);
    }

    /**
     * 获取消息详情
     */
    public function show(int $id): JsonResponse
    {
        $query = new MessageFindQuery(
            id: $id,
            owner: $this->getOwner(),
            include: ['category', 'template', 'pushLogs'],
        );

        $message = $this->service->find($query);

        if (!$message) {
            return $this->jsonNotFound('消息不存在');
        }

        return $this->jsonSuccess(new static::$resourceClass($message));
    }

    /**
     * 发送消息
     */
    public function send(MessageSendRequest $request): JsonResponse
    {
        $command = MessageSendCommand::from([
            ...$request->validated(),
            'sender' => $this->getOwner(),
        ]);

        $messages = $this->service->send($command);

        return $this->jsonCreated([
            'message' => '消息发送成功',
            'data' => static::$resourceClass::collection($messages),
            'count' => count($messages),
        ]);
    }

    /**
     * 创建消息
     */
    public function store(MessageCreateRequest $request): JsonResponse
    {
        $command = MessageCreateCommand::from([
            ...$request->validated(),
            'owner' => $this->getOwner(),
            'receiver' => $this->getOwner(), // 创建给自己的消息
        ]);

        $message = $this->service->create($command);

        return $this->jsonCreated(new static::$resourceClass($message));
    }

    /**
     * 标记消息为已读
     */
    public function markAsRead(MessageMarkAsReadRequest $request): JsonResponse
    {
        $command = MessageMarkAsReadCommand::from([
            ...$request->validated(),
            'reader' => $this->getOwner(),
        ]);

        $count = $this->service->markAsRead($command);

        return $this->jsonSuccess([
            'message' => '标记成功',
            'updated_count' => $count,
        ]);
    }

    /**
     * 标记单条消息为已读
     */
    public function read(int $id): JsonResponse
    {
        $command = new MessageMarkAsReadCommand(
            messageIds: $id,
            reader: $this->getOwner(),
        );

        $count = $this->service->markAsRead($command);

        return $this->jsonSuccess([
            'message' => '标记成功',
            'updated_count' => $count,
        ]);
    }

    /**
     * 标记所有消息为已读
     */
    public function markAllAsRead(): JsonResponse
    {
        $command = new MessageMarkAsReadCommand(
            messageIds: [],
            reader: $this->getOwner(),
            markAll: true,
        );

        $count = $this->service->markAsRead($command);

        return $this->jsonSuccess([
            'message' => '全部标记成功',
            'updated_count' => $count,
        ]);
    }

    /**
     * 获取未读消息数量
     */
    public function unreadCount(): JsonResponse
    {
        $receiverId = (string) $this->getOwner()->getKey();
        $count = $this->service->getUnreadCount($receiverId);

        return $this->jsonSuccess([
            'unread_count' => $count,
        ]);
    }

    /**
     * 获取消息统计
     */
    public function statistics(): JsonResponse
    {
        $query = new MessageStatisticsQuery(
            owner: $this->getOwner(),
            receiverId: (string) $this->getOwner()->getKey(),
        );

        $statistics = $this->service->statistics($query);

        return $this->jsonSuccess($statistics);
    }

    /**
     * 获取高优先级未读消息
     */
    public function highPriorityUnread(): JsonResponse
    {
        $receiverId = (string) $this->getOwner()->getKey();
        $messages = $this->service->getHighPriorityUnread($receiverId, 10);

        return $this->jsonSuccess(static::$resourceClass::collection($messages));
    }

    /**
     * 归档消息
     */
    public function archive(int $id): JsonResponse
    {
        $message = $this->service->find(new MessageFindQuery($id, $this->getOwner()));

        if (!$message) {
            return $this->jsonNotFound('消息不存在');
        }

        $message->archive();

        return $this->jsonSuccess([
            'message' => '归档成功',
        ]);
    }

    /**
     * 批量归档消息
     */
    public function batchArchive(array $messageIds): JsonResponse
    {
        $count = $this->service->batchArchive($messageIds);

        return $this->jsonSuccess([
            'message' => '批量归档成功',
            'updated_count' => $count,
        ]);
    }

    /**
     * 权限验证
     */
    public function authorize($ability, $arguments = []): bool
    {
        // 用户只能操作自己的消息
        return true;
    }
}
