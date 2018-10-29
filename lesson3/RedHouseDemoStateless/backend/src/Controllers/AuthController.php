<?php

namespace App\Controllers;

use IlyaZelen\UserOauth2Services\OauthProvidersManager;

class AuthController
{
    public function url(string $provider): string
    {
        $scopes = [
            'google' => ['https://www.googleapis.com/auth/plus.login']
        ];

        return OauthProvidersManager
            ::provider($provider)
            ::scopes($scopes[$provider] ?? [])
            ::getAuthUrl();
    }

    public function user(string $provider): string
    {
        // получает токен доступа по GET параметру code, после чего получает попользователя по токену (userByToken)
        $user = OauthProvidersManager
            ::provider($provider)
            ::user();

        return userObjToJson($user);
    }

    public function userByToken()
    {
        $provider = (string) $_GET['provider'];
        $token = (string) $_GET['token'];

        $user = OauthProvidersManager
            ::provider($provider)
            ::getUserByToken($token);

        return userObjToJson($user);
    }
}

function userObjToJson ($userObj) {
    return \json_encode([
        'user' => [
            'id' => $userObj->getId(),
            'name' => $userObj->getName(),
            'email' => $userObj->getEmail(),
            'avatar' => $userObj->getAvatar(),
            'nickname' => $userObj->getNickname()
        ],
        'originalUser' => $userObj->getOriginal(),
        'token' => $userObj->accessToken
    ]);
}
