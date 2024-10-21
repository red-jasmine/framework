<?php

namespace RedJasmine\Tests;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Workbench\App\Models\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    //
    use WithWorkbench;


    protected function setUp() : void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->actingAs(User::find(1));
    }


    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;


    protected function defineEnvironment($app)
    {

        tap($app['config'], function (Repository $config) {
            $config->set('app.locale', 'zh_CN');
            $config->set('app.faker_locale', 'zh_CN');
        });

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
}
