<?php

use Illuminate\Support\Facades\Auth;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserBindCommand;
use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserUnbindCommand;
use RedJasmine\Socialite\Application\Services\SocialiteUserApplicationService;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserRepositoryInterface;
use RedJasmine\Support\Domain\Data\UserData;

beforeEach(function () {

    $this->commandService = app(SocialiteUserApplicationService::class);
    $this->repository     = app(SocialiteUserRepositoryInterface::class);

});


test('can  bind a user', function () {

    $command = new SocialiteUserBindCommand();

    $command->appId    = fake()->numerify('app-##########');
    $command->clientId = fake()->numerify('client-##########');
    $command->identity = fake()->uuid();
    $command->provider = fake()->randomElement(['weixin', 'aliapy']);
    $command->owner    = Auth::user();


    $result = $this->commandService->bind($command);

    $this->assertEquals(true, $result);

    $query = SocialiteUserFindUserQuery::from([
        'provider' => $command->provider,
        'clientId' => $command->clientId,
        'identity' => $command->identity,
        'appId'    => $command->appId,

    ]);

    return $this->repository->findUser($query);

});


test('can unbind a user', function (SocialiteUser $socialiteUser) {

    $command = new SocialiteUserUnbindCommand();

    $command->provider = $socialiteUser->provider;
    $command->appId    = $socialiteUser->app_id;
    $command->clientId = $socialiteUser->client_id;
    $command->identity = $socialiteUser->identity;

    $command->owner = UserData::from([
        'type' => $socialiteUser->owner_type,
        'id'   => $socialiteUser->owner_id
    ]);

    $result = $this->commandService->unbind($command);
    $this->assertEquals(true, $result);
})->depends('can  bind a user');



