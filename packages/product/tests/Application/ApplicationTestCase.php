<?php

namespace RedJasmine\Product\Tests\Application;

use RedJasmine\Product\Tests\Fixtures\Users\User;
use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Facades\ServiceContext;

class ApplicationTestCase extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        ServiceContext::setOperator($this->user());
    }

    public function user() : UserInterface
    {
        return User::make(1);
    }

}
