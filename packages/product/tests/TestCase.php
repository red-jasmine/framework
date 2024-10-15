<?php

namespace RedJasmine\Product\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use function Orchestra\Testbench\artisan;


class TestCase extends \Orchestra\Testbench\TestCase
{

    use WithWorkbench;
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function defineEnvironment($app)
    {


    }

    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;


    /**
     * Get the application timezone.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'Asia/Shanghai';
    }


    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        artisan($this, 'migrate');

        // $this->beforeApplicationDestroyed(
        //     //fn () => artisan($this, 'migrate:rollback')
        // );

    }

    protected function getPackageProviders($app)
    {
        return [
            "\\RedJasmine\\Product\\Application\\ProductApplicationServiceProvider",
            "\\RedJasmine\\Product\\ProductPackageServiceProvider"
        ];
    }


}
