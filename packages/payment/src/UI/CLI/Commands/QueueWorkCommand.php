<?php

namespace RedJasmine\Payment\UI\CLI\Commands;

use Illuminate\Console\Command;

class QueueWorkCommand extends Command
{
    protected $signature = 'payment:queue:work';

    protected $description = '运行 queue:work 命令';

    public function handle() : void
    {
        // 运行  支付命令
        $this->call('queue:work');

    }
}
