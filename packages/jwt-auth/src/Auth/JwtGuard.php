<?php

namespace RedJasmine\JwtAuth\Auth;

class JwtGuard extends \Tymon\JWTAuth\JWTGuard
{

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if ($this->jwt->setRequest($this->request)->getToken() &&
            ($payload = $this->jwt->check(true)) &&
            $this->validateSubject()
        ) {
            // 传入载荷
            return $this->user = $this->provider->retrieveById($payload);
        }
    }
}
