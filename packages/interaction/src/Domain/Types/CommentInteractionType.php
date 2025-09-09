<?php

namespace RedJasmine\Interaction\Domain\Types;

use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\This;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Models\Records\InteractionRecordComment;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use Spatie\QueryBuilder\AllowedFilter;

class CommentInteractionType extends BaseInteractionType
{


    protected InteractionRecordRepositoryInterface $recordRepository;

    public function __construct($config)
    {
        $this->recordRepository = app(InteractionRecordRepositoryInterface::class);
    }

    public function allowedFields() : array
    {
        return [
            AllowedFilter::exact('is_good'),
            AllowedFilter::exact('is_hot'),
            AllowedFilter::exact('is_top'),
            AllowedFilter::exact('root_id'),
            AllowedFilter::exact('parent_id'),
        ];
    }


    public function getModelClass() : string
    {
        return InteractionRecordComment::class;
    }

    public function validate(InteractionData $data) : void
    {
        $data->quantity = 1;
        $parentId       = (int) ($data->extra['parent_id'] ?? 0);

        if ($parentId) {
            $query                  = FindQuery::from([]);
            $query->resourceType    = $data->resourceType;
            $query->resourceId      = $data->resourceId;
            $query->interactionType = $data->interactionType;
            $query->setKey($parentId);
            $parent                    = $this->recordRepository->find($query);
            $data->extra['root_id']   = ($parent->root_id === 0) ? $parent->id : $parent->root_id;
            $data->extra['parent_id'] = $parent->id;
        }

        // TODO 内容过滤
        parent::validate($data);
    }


    public function makeRecord(InteractionData $data) : InteractionRecordComment
    {

        $interactionRecord                   = InteractionRecordComment::make();
        $interactionRecord->resource_type    = $data->resourceType;
        $interactionRecord->resource_id      = $data->resourceId;
        $interactionRecord->interaction_type = $data->interactionType;
        $interactionRecord->quantity         = $data->quantity;
        $interactionRecord->owner            = $data->user;
        $interactionRecord->interaction_time = Carbon::now();
        $interactionRecord->setExtra($data->extra);
        return $interactionRecord;
    }


}
