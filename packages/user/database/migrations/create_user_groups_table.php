<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\User\Domain\Enums\UserGroupEnum;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->category('用户-分组');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('user_groups');
    }
};
