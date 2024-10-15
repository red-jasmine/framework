<?php

namespace RedJasmine\FilamentCore\Commands;

use Illuminate\Console\Command;

class FilamentCoreCommand extends Command
{
    public $signature = 'filament-core';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
