<?php

namespace RedJasmine\FilamentOrder\Commands;

use Illuminate\Console\Command;

class FilamentOrderCommand extends Command
{
    public $signature = 'filament-order';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
