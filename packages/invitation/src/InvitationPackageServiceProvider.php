<?php

namespace RedJasmine\Invitation;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeReadRepositoryInterface;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Domain\Repositories\InvitationRecordRepositoryInterface;
use RedJasmine\Invitation\Infrastructure\ReadRepositories\Mysql\InvitationCodeReadRepository;
use RedJasmine\Invitation\Infrastructure\Repositories\Eloquent\InvitationCodeRepository;
use RedJasmine\Invitation\Infrastructure\Repositories\Eloquent\InvitationRecordRepository;

/**
 * 邀请包服务提供者
 */
class InvitationPackageServiceProvider extends PackageServiceProvider
{


    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-invitation')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'create_invitation_codes_table',
                'create_invitation_records_table',
            ])
            ->runsMigrations();
    }


} 