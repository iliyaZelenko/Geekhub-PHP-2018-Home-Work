<?php

namespace IlyaZelen\UserOauth2Services\Providers;

use IlyaZelen\UserOauth2Services\Contracts\ProviderContract;
use IlyaZelen\UserOauth2Services\Contracts\UserContract;
use IlyaZelen\UserOauth2Services\User\User;

/**
 * @see https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow
 */
class FacebookProvider extends AbstractProvider
{
    /**
     * Версия API.
     *
     * @var string
     */
    protected static $version = 'v3.0';

    /**
     * Базовый URL.
     *
     * @var string
     */
    protected static $APIBaseUrl = 'https://graph.facebook.com';

    /**
     * Отображать диалоговое меню в попап окне.
     *
     * @var bool
     */
    protected static $popup = false;

    /**
     * Повторный запрос если отклонено разрешение.
     *
     * @see https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow#reaskperms
     *
     * @var bool
     */
    protected static $reRequest = false;

    /**
     * Зарпашиваемые поля пользователя.
     *
     * @var array
     */
    protected static $fields = ['name', 'email', 'gender', 'verified', 'link'];

    /**
     * {@inheritdoc}
     */
    protected static function getAuthUrlBase(): string
    {
        return 'https://www.facebook.com/' . static::$version . '/dialog/oauth';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getUserUrl(string $accessToken): string
    {
        $url = static::$APIBaseUrl . '/' . static::$version . '/me?access_token=' . $accessToken . '&fields=' . implode(',', static::$fields);

        if (! empty(static::$providerConfig['clientSecret'])) {
            // facebook требуте передать токен в хешированном виде, для хешированив в роли ключа выступает static::$providerConfig['clientSecret']
            $appSecretProof = hash_hmac('sha256', $accessToken, static::$providerConfig['clientSecret']);
            $url .= '&appsecret_proof=' . $appSecretProof;
        }

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    protected static function getAccessTokenUrl(): string
    {
        return static::$APIBaseUrl . '/' . static::$version . '/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     *
     * @see supported scopes: https://developers.facebook.com/docs/facebook-login/permissions/
     */
    protected static function getScopes(): array
    {
        // supported scopes: https://developers.facebook.com/docs/facebook-login/permissions/
        return [
            'email'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected static function transformUserResponseToObject(array $user): UserContract {
        $avatarUrl = static::$APIBaseUrl . '/' . static::$version . '/' . $user['id'] . '/picture';

        // фейсбук очень жадный на данные
        return (new User($user))
            ->setFields([
                'id' => $user['id'],
                'nickname' => null, // фейсбук не поддерживает ник
                'name' => $user['name'] ?? null,
                'email' => $user['email'] ?? null,
                'avatar' => $avatarUrl . '?type=normal'
            ])->setAdditionalFields([
                'avatar_original' => $avatarUrl . '?width=1920',
                'profileUrl' => $user['link'] ?? null
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected static function getAuthUrlQueryParams(): array
    {
        $fields = parent::{__FUNCTION__}();

        if (static::$popup) {
            $fields['display'] = 'popup';
        }
        if (static::$reRequest) {
            $fields['auth_type'] = 'rerequest';
        }

        return $fields;
    }

}
