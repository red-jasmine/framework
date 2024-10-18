<?php

namespace RedJasmine\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\Concerns\WithWorkbench;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    //
    use WithWorkbench;
    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;

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
