<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('promoter_teams', function (Blueprint $table) {
            $table->category('推广员团队');
            $table->unsignedTinyInteger('leader_id')->nullable()->comment('团长ID');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('promoter_teams');
    }
};
