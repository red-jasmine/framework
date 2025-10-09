<?php

namespace RedJasmine\Invitation\Application\Pipelines\UserRegister;

use Closure;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Domain\Data\UseInvitationCodeData;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Domain\Repositories\InvitationRecordRepositoryInterface;
use RedJasmine\UserCore\Application\Services\Commands\Register\UserRegisterCommand;
use RedJasmine\UserCore\Domain\Models\User;

class UserRegisterPipeline
{
    public function __construct(
        protected InvitationCodeApplicationService $service,
        protected InvitationCodeRepositoryInterface $invitationCodeRepository,
        protected InvitationRecordRepositoryInterface $invitationRecordRepository
    ) {
    }

    /**
     * @param  UserRegisterCommand  $data
     * @param  Closure  $next
     *
     * @return User
     */
    public function handle(UserRegisterCommand $data, Closure $next) : User
    {
        // 验证邀请码 ,获取邀请人

        /**
         * @var User $user
         */
        $user = $next($data);
        // 设置邀请人
        $invitationData = UserRegisterInvitationData::from($data->context['invitation'] ?? []);

        if ($invitationData->code) {
            $invitationCode = $this->invitationCodeRepository->findByCode($invitationData->code);
            // 设置邀请码使用 TODO
            $useInvitationCodeData          = new  UseInvitationCodeData;
            $useInvitationCodeData->code    = $invitationData->code;
            $useInvitationCodeData->invitee = $user;

            $this->service->use($useInvitationCodeData);
            $user->inviter = $invitationCode->owner;
        }


        return $user;
    }

}