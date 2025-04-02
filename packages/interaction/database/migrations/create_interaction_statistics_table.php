<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('interaction_statistics', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('resource_type', 64)->comment('资源类型');
            $table->string('resource_id', 64)->comment('资源ID');
            $table->string('interaction_type', 64)->comment('互动类型');
            $table->unsignedBigInteger('quantity')->default(0)->comment('数量');
            $table->timestamps();

            $table->unique(['resource_id', 'resource_type', 'interaction_type'], 'uk_resource_interaction');
            $table->comment('互动资源统计表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('interaction_statistics');
    }
};
