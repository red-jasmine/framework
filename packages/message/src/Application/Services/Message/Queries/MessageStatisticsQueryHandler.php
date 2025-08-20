<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Application\Services\Message\Queries;

use RedJasmine\Message\Application\Services\Category\MessageCategoryApplicationService;
use RedJasmine\Message\Application\Services\Category\Queries\MessageCategoryTreeQuery;
use RedJasmine\Message\Application\Services\Message\MessageApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

/**
 * 消息统计查询处理器
 */
class MessageStatisticsQueryHandler extends QueryHandler
{
    public function __construct(
        protected MessageApplicationService $service,
        protected MessageCategoryApplicationService $messageCategoryService
    ) {
    }

    /**
     * 处理消息统计查询
     */
    /**
     * @param  MessageStatisticsQuery  $query
     *
     * @return array{tree:array,total:int}
     */
    public function handle(MessageStatisticsQuery $query) : array
    {

        // 查询分类树
        $treeQuery = MessageCategoryTreeQuery::from($query);

        $tree = $this->messageCategoryService->tree($treeQuery);


        $counts = $this->service->readRepository->getUnreadStatistics($query->owner, $query->biz);
        // 累加 所有  $counts 值
        $total = array_sum($counts);
        $this->setCategoryTreeUnreadCount($tree, $counts);
        $counts['all'] = $total;
        return $counts;

    }

    public function setCategoryTreeUnreadCount($tree, &$counts) : int
    {
        $count = 0;
        foreach ($tree as $category) {
            $count += $counts[$category->id] ?? 0;
            if ($category->children) {
                $count = $count + $this->setCategoryTreeUnreadCount($category->children, $counts);


            }
            $counts[$category->id] = $count;
        }
        return $count;

    }


}
