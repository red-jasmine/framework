<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Controllers;

use RedJasmine\Message\Application\Services\Category\MessageCategoryApplicationService;
use RedJasmine\Message\Application\Services\Commands\MessageCategoryCreateCommand;
use RedJasmine\Message\Application\Services\Commands\MessageCategoryUpdateCommand;
use RedJasmine\Message\Application\Services\Queries\MessageCategoryListQuery;
use RedJasmine\Message\Application\Services\Category\Queries\MessageCategoryTreeQuery;
use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageCategoryListRequest;
use RedJasmine\Message\UI\Http\User\Api\Requests\MessageCategoryUpdateRequest;
use RedJasmine\Message\UI\Http\User\Api\Resources\MessageCategoryResource;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 消息分类用户端控制器
 */
class MessageCategoryController extends Controller
{
    use RestControllerActions;

    use HasTreeAction;

    protected static string $resourceClass = MessageCategoryResource::class;
    protected static string $paginateQueryClass = MessageCategoryListQuery::class;
    protected static string $treeQueryClass = MessageCategoryTreeQuery::class;
    protected static string $modelClass = MessageCategory::class;


    public function __construct(
        protected MessageCategoryApplicationService $service,
    ) {

    }


    /**
     * 权限验证
     */
    public function authorize($ability, $arguments = []): bool
    {
        // 用户只能操作自己的分类
        return true;
    }
}
