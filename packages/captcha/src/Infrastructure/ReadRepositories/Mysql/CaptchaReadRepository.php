<?php

namespace RedJasmine\Captcha\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Repositories\CaptchaReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class CaptchaReadRepository extends QueryBuilderReadRepository implements CaptchaReadRepositoryInterface
{

    public static string $modelClass = Captcha::class;


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('app'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('notifiable_type'),
            AllowedFilter::exact('notifiable_id'),
        ];
    }
}