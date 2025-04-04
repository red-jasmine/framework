<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Community\Domain\Models\Enums\ContentTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('topic_contents', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->unsignedBigInteger('topic_id')->comment('id');
            $table->string('content_type')->default(ContentTypeEnum::TEXT)->comment('内容类型');
            $table->longText('content')->comment('内容');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('topic_contents');
    }
};
