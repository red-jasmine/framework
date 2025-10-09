<?php

use RedJasmine\User\Infrastructure\Migrations\Migration;

return new class extends Migration {
    protected string $name  = 'admin';
    protected string $label = '管理员';
};
