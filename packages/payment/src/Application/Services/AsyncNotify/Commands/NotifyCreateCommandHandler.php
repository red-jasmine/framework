<?php

namespace RedJasmine\Payment\Application\Services\AsyncNotify\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Payment\Application\Services\AsyncNotify\AsyncNotifyCommandService;
use RedJasmine\Support\Application\Commands\CreateCommandHandler;
use RedJasmine\Support\Data\Data;

class NotifyCreateCommandHandler extends CreateCommandHandler
{
    public function __construct(AsyncNotifyCommandService $service)
    {
        parent::__construct($service);
    }

    public function handle(Data $command) : ?Model
    {
        $model = parent::handle($command);


        return $model;
    }


}
