<?php

namespace App\Controllers;

use IlyaZelen\UserOauth2Services\OauthProvidersManager;

class AuthController
{
    public function signin(string $provider): void
    {
        $scopes = [
            'google' => ['https://www.googleapis.com/auth/plus.login']
        ];

        OauthProvidersManager
            ::provider($provider)
            ::scopes($scopes[$provider] ?? [])
            ::redirect();
    }

    public function redirect(string $provider): void
    {
        $_SESSION['user'] = OauthProvidersManager
            ::provider($provider)
            ::user();

        redirectHome();
    }

    public function logout(): void
    {
        unset($_SESSION['user']);

        redirectHome();
    }
}

function redirectHome () {
    header('Location: /');
    die();
}
