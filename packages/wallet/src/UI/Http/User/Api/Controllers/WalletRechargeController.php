<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;
use RedJasmine\Wallet\Application\Commands\Recharge\CreateRechargeCommand;
use RedJasmine\Wallet\Application\Queries\Recharge\WalletRechargeDetailQuery;
use RedJasmine\Wallet\Application\Queries\Recharge\WalletRechargeListQuery;
use RedJasmine\Wallet\Application\Services\Recharge\Queries\WalletRechargePaginateQuery;
use RedJasmine\Wallet\Application\Services\Recharge\WalletRechargeApplicationService;
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
    ) {
        // 设置查询作用域
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }


    /**
     * 发起充值
     */
    public function store(CreateRechargeRequest $request)
    {
        $command                = new CreateRechargeCommand();
        $command->owner         = $this->getOwner();
        $command->walletType    = $request->get('wallet_type');
        $command->amount        = $request->get('amount');
        $command->paymentMethod = $request->get('payment_method');
        $command->remark        = $request->get('remark');
        $recharge               = $this->service->create($command);

        return new WalletRechargeResource($recharge);
    }

    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }
} 