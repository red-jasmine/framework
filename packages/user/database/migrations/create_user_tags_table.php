<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\User\Domain\Enums\UserTagStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('user_tags', function (Blueprint $table) {
            $table->category('用户-用户标签');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('user_tags');
    }
};
