<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Distribution\Application\PromoterBindUser\PromoterBindUserApplicationService;
use RedJasmine\Distribution\Application\PromoterBindUser\Commands\PromoterBindUserCommand;
use RedJasmine\Distribution\Application\PromoterBindUser\Queries\PromoterBindUserPaginateQuery;
use RedJasmine\Distribution\UI\Http\User\Api\Resources\PromoterBindUserResource;
use RedJasmine\Support\UI\Http\Controllers\Controller;

final class PromoterBindUserController extends Controller
{
    public function __construct(
        protected PromoterBindUserApplicationService $service
    ) {
    }

    /**
     * 查询当前用户的绑定关系
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = PromoterBindUserPaginateQuery::from($request->all());
        $query->userId = $request->user()->getID();
        $query->userType = $request->user()->getType();

        $results = $this->service->paginate($query);

        return PromoterBindUserResource::collection($results);
    }

    /**
     * 绑定分销员（通过邀请码）
     */
    public function store(Request $request): PromoterBindUserResource
    {
        $request->validate([
            'promoter_id' => 'required|integer',
            'invitation_code' => 'nullable|string'
        ]);

        $command = PromoterBindUserCommand::from([
            'promoterId' => $request->input('promoter_id'),
            'user' => $request->user(),
            'bindReason' => '用户主动绑定',
            'invitationCode' => $request->input('invitation_code')
        ]);

        $result = $this->service->bind($command);

        return PromoterBindUserResource::make($result);
    }
} 