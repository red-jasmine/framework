<?php

namespace RedJasmine\Order\Tests;

use Illuminate\Foundation\Application;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use Orchestra\Testbench\Concerns\WithWorkbench;
use function Orchestra\Testbench\artisan;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;

    // use DatabaseTransactions;


    protected function getOperator() : UserInterface
    {
        return new UserData(
            type:     'console',
            id:       fake()->numberBetween(1000000, 999999999),
            nickname: 'Console',
            avatar:   null
        );

    }


    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function defineEnvironment($app):void
    {


    }


    /**
     * Get the application timezone.
     *
     * @param Application $app
     *
     * @return string
     */
    protected function getApplicationTimezone($app):string
    {
        return 'Asia/Shanghai';
    }

    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            'RedJasmine\Order\OrderPackageServiceProvider',
            "RedJasmine\Order\Application\OrderApplicationServiceProvider",
        ];
    }

    protected function defineDatabaseMigrations()
    {
        artisan($this, 'migrate');


    }

}
