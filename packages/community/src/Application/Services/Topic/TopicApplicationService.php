<?php

namespace RedJasmine\Community\Application\Services\Topic;

use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Community\Domain\Transformer\TopicTransformer;
use RedJasmine\Comnunity\Domain\Repositories\TopicReadRepositoryInterface;
use RedJasmine\Comnunity\Domain\Repositories\TopicRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Commands\ApprovalCommandHandler;
use RedJasmine\Support\Application\Commands\SubmitApprovalCommandHandler;

class TopicApplicationService extends ApplicationService
{

    public function __construct(
        public TopicRepositoryInterface $repository,
        public TopicReadRepositoryInterface $readRepository,
        public TopicTransformer $transformer
    ) {
    }

    protected static string $modelClass = Topic::class;

    protected static $macros = [
        'approval'       => ApprovalCommandHandler::class,
        'submitApproval' => SubmitApprovalCommandHandler::class,
    ];

}