<?php

namespace IlyaZelen\UserOauth2Services\Contracts;

interface UserTokenContract {
    /**
     * Ставит токен доступа.
     *
     * @param  string $accessToken
     * @return $this
     */
    public function setToken(string $accessToken);

    /**
     * Ставит токен обновления требующийся для обновленяи токена.
     *
     * @param string $refreshToken токен обновления токена доступа
     * @return $this
     */
    public function setRefreshToken(string $refreshToken = null);

    /**
     * Ставит срок жизни токена в секундах (не Unix время).
     *
     * @param int $expiresIn кол-во секунд
     * @return $this
     */
    public function setExpiresIn(int $expiresIn = null);
}
