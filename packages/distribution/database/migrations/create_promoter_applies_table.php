<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyAuditStatusEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterAuditMethodEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('promoter_applies', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('promoter_id')->comment('分销员ID');
            $table->unsignedTinyInteger('level')->default(1)->comment('等级');
            $table->string('apply_type', 32)->comment(PromoterApplyTypeEnum::comments('类型'));
            $table->string('apply_method', 32)->comment(PromoterApplyMethodEnum::comments('申请方式'));
            $table->string('audit_method', 32)->comment(PromoterAuditMethodEnum::comments('审核方式'));
            $table->string('audit_status', 32)->comment(PromoterApplyAuditStatusEnum::comments('审核状态'));
            $table->timestamp('apply_at')->comment('申请时间');
            $table->timestamp('audit_at')->nullable()->comment('审核时间');
            $table->string('audit_reason')->nullable()->comment('审核原因');
            $table->json('extra')->nullable()->comment('扩展字段');
            $table->userMorphs('auditor', '审核员');
            $table->operator();
            $table->softDeletes();
            $table->comment('分销员申请记录');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('promoter_applies');
    }
};
