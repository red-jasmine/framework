<?php

namespace Redjasmine\FilamentProduct\Commands;

use Illuminate\Console\Command;

class FilamentProductCommand extends Command
{
    public $signature = 'filament-product';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
