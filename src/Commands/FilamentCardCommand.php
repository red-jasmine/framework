<?php

namespace RedJasmine\FilamentCard\Commands;

use Illuminate\Console\Command;

class FilamentCardCommand extends Command
{
    public $signature = 'filament-card';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
