<?php

namespace RedJasmine\Captcha\Infrastructure\Repositories;

use RedJasmine\Captcha\Domain\Data\CaptchaData;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Repositories\CaptchaRepositoryInterface;
use RedJasmine\Support\Foundation\Facades\AES;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 验证码仓库实现
 *
 * 基于Repository实现，提供验证码实体的读写操作能力
 */
class CaptchaRepository extends Repository implements CaptchaRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Captcha::class;

    /**
     * 根据通知对象查找最后一个验证码
     */
    public function findLastCodeByNotifiable(CaptchaData $captchaData) : ?Captcha
    {
        return static::$modelClass::query()
                                          ->where('notifiable_type', $captchaData->notifiableType)
                                          ->where('notifiable_id', AES::encryptString($captchaData->notifiableId))
                                          ->where('type', $captchaData->type)
                                          ->where('app', $captchaData->app)
                                          ->orderByDesc('id')
                                          ->first();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('app'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('notifiable_type'),
            AllowedFilter::exact('notifiable_id'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null) : array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }
}
