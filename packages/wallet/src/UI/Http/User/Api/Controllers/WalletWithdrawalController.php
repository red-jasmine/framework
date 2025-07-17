<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;
use RedJasmine\Wallet\Application\Commands\Withdrawal\CreateWithdrawalCommand;
use RedJasmine\Wallet\Application\Queries\Withdrawal\WalletWithdrawalDetailQuery;
use RedJasmine\Wallet\Application\Queries\Withdrawal\WalletWithdrawalListQuery;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Queries\WalletWithdrawalPaginateQuery;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\UI\Http\User\Api\Requests\CreateWithdrawalRequest;
use RedJasmine\Wallet\UI\Http\User\Api\Resources\WalletWithdrawalResource;

class WalletWithdrawalController extends Controller
{
    protected static string $resourceClass      = WalletWithdrawalResource::class;
    protected static string $modelClass         = WalletWithdrawal::class;
    protected static string $paginateQueryClass = WalletWithdrawalPaginateQuery::class;

    use RestQueryControllerActions;


    public function __construct(
        protected WalletWithdrawalApplicationService $service,
    ) {
        // 设置查询作用域
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }


    /**
     * 发起提现
     */
    public function store(CreateWithdrawalRequest $request)
    {

        // TODO 需要先做 绑卡操作


        $command = WalletWithdrawalCreateCommand::from($request);


        $command->owner      = $this->getOwner();
        $command->walletType = $request->get('wallet_type');
        $command->amount     = $request->get('amount');
        $command->bankCardId = $request->get('bank_card_id');
        $command->remark     = $request->get('remark');
        $withdrawal          = $this->service->create($command);

        return new WalletWithdrawalResource($withdrawal);
    }

    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }
} 