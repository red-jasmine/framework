<?php

namespace RedJasmine\ResourceUsage\Commands;

use Illuminate\Console\Command;

class ResourceUsageCommand extends Command
{
    public $signature = 'resource-usage';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
