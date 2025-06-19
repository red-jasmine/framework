<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('promoter_groups', function (Blueprint $table) {
            $table->category('推广员分组');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('promoter_groups');
    }
};
