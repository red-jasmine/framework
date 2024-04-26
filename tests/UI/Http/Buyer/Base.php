<?php

namespace RedJasmine\Order\Tests\UI\Http\Buyer;

use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Tests\Fixtures\Users\User;
use RedJasmine\Order\Tests\TestCase;
use RedJasmine\Order\UI\Http\Buyer\Api\OrderBuyerApiRoute;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;

class Base extends TestCase
{

    /**
     * Define routes setup.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function defineRoutes($router)
    {
        // Define routes.
        $router->group([
                           'prefix' => 'api/buyer'
                       ], function () {
            OrderBuyerApiRoute::route();
        });


    }

    protected function user() : User
    {
        $user = User::make(1);
        $this->actingAs($user);
        return $user;
    }

    protected function owner()
    {
        $user = $this->user();
        if ($user instanceof BelongsToOwnerInterface) {
            return $user->owner();
        }
        return $user;

    }


    protected function orderCommandService() : OrderCommandService
    {
        return app(OrderCommandService::class)->setOperator($this->getOperator());
    }

    protected function refundCommandService() : RefundCommandService
    {
        return app(RefundCommandService::class)->setOperator($this->getOperator());
    }
}
