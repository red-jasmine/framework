<?php

declare(strict_types = 1);

namespace RedJasmine\Message\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Message\Application\Services\Message\Commands\MessageCreateCommand;
use RedJasmine\Message\Application\Services\Message\Commands\MessageMarkAsReadCommand;
use RedJasmine\Message\Application\Services\Message\MessageApplicationService;
use RedJasmine\Message\Application\Services\Message\Queries\MessageFindQuery;
use RedJasmine\Message\Application\Services\Message\Queries\MessagePaginateQuery;
use RedJasmine\Message\Application\Services\Message\Queries\MessageStatisticsQuery;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageMarkAsReadRequest;
use RedJasmine\Message\UI\Http\User\Api\Resources\MessageResource;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

/**
 * 消息用户端控制器
 */
class MessageController extends Controller
{
    use RestQueryControllerActions;


    protected static string $resourceClass      = MessageResource::class;
    protected static string $paginateQueryClass = MessagePaginateQuery::class;
    protected static string $findQueryClass     = MessageFindQuery::class;
    protected static string $modelClass         = Message::class;
    protected static string $dataClass          = MessageCreateCommand::class;

    public function __construct(
        protected MessageApplicationService $service,
    ) {

    }

    public function read($id, Request $request) : JsonResponse
    {
        $model = $this->findOne($id, $request);

        $this->injectionOwnerRequest();
        $command = MessageMarkAsReadCommand::from($request);
        $command->setKey($id);

        $this->service->markAsRead($command);

        return static::success();
    }


    /**
     * 标记消息为已读
     */
    public function markAsRead(MessageMarkAsReadRequest $request) : JsonResponse
    {
        $this->injectionOwnerRequest();
        $command = MessageMarkAsReadCommand::from($request);


        $this->service->markAsRead($command);

        return static::success();
    }


    /**
     * 标记所有消息为已读
     */
    public function allMarkAsRead(Request $request) : JsonResponse
    {
        $this->injectionOwnerRequest();
        $command = MessageMarkAsReadCommand::from($request);

        $this->service->allMarkAsRead($command);

        return static::success();
    }

    /**
     * 获取未读消息数量
     */
    public function unreadCount(Request $request) : JsonResponse
    {

        $count = $this->service->getUnreadCount($this->getOwner(), $request->get('biz', 'default'));

        return static::success(['count' => $count]);
    }

    /**
     * 获取消息统计
     */
    public function statistics(Request $request) : JsonResponse
    {

        $this->injectionOwnerRequest();
        $query = MessageStatisticsQuery::from($request);


        $result = $this->service->statistics($query);

        return static::success($result);

    }


    /**
     * 权限验证
     */
    public function authorize($ability, $arguments = []) : bool
    {
        // 用户只能操作自己的消息
        return true;
    }
}
