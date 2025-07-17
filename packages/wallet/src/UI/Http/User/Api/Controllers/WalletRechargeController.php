<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;
use RedJasmine\Wallet\Application\Queries\Recharge\WalletRechargeDetailQuery;
use RedJasmine\Wallet\Application\Queries\Recharge\WalletRechargeListQuery;
use RedJasmine\Wallet\Application\Services\Recharge\Commands\CompletePaymentCommand;
use RedJasmine\Wallet\Application\Services\Recharge\Commands\CreateRechargeCommand;
use RedJasmine\Wallet\Application\Services\Recharge\Queries\WalletRechargePaginateQuery;
use RedJasmine\Wallet\Application\Services\Recharge\WalletRechargeApplicationService;
use RedJasmine\Wallet\Application\Services\Wallet\Queries\FindByOwnerTypeQuery;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\UI\Http\User\Api\Requests\CreateRechargeRequest;
use RedJasmine\Wallet\UI\Http\User\Api\Resources\WalletRechargeResource;

class WalletRechargeController extends Controller
{
    protected static string $resourceClass      = WalletRechargeResource::class;
    protected static string $modelClass         = WalletRecharge::class;
    protected static string $paginateQueryClass = WalletRechargePaginateQuery::class;

    use RestQueryControllerActions;


    public function __construct(
        protected WalletRechargeApplicationService $service,
        protected WalletApplicationService $walletApplicationService,
    ) {
        // 设置查询作用域
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }


    /**
     * @param  CreateRechargeRequest  $request
     *
     * @return WalletRechargeResource
     */
    public function store(CreateRechargeRequest $request) : WalletRechargeResource
    {
        $query        = new FindByOwnerTypeQuery();
        $query->owner = $this->getOwner();
        $query->type  = $request->wallet_type;
        $wallet       = $this->walletApplicationService->findByOwnerType($query);
        $command      = CreateRechargeCommand::from($request);
        $command->setKey($wallet->id);
        $recharge = $this->service->create($command);

        return new WalletRechargeResource($recharge);
    }


    public function payment($id, Request $request)
    {
        // 发起支付


    }


    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }
} 