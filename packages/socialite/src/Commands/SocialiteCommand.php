<?php

namespace RedJasmine\Socialite\Commands;

use Illuminate\Console\Command;

class SocialiteCommand extends Command
{
    public $signature = 'socialite';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
