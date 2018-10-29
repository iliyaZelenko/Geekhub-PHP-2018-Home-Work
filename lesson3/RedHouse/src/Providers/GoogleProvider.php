<?php

namespace IlyaZelen\UserOauth2Services\Providers;

use IlyaZelen\UserOauth2Services\Contracts\ProviderContract;
use IlyaZelen\UserOauth2Services\Contracts\UserContract;
use IlyaZelen\UserOauth2Services\User\User;

class GoogleProvider extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    protected static function getAuthUrlBase(): string
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getUserUrl(string $accessToken): string
    {
        return 'https://www.googleapis.com/plus/v1/people/me?';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getAccessTokenUrl(): string
    {
        return 'https://accounts.google.com/o/oauth2/token';
    }

    /**
     * {@inheritdoc}
     *
     * @see supported scopes: https://developers.google.com/identity/protocols/googlescopes#plusv1
     */
    protected static function getScopes(): array
    {
        // supported scopes: https://developers.google.com/identity/protocols/googlescopes#plusv1
        return [
            'openid',
            'profile',
            'email',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected static function getUserByAccessTokenResponseRequestOptions(string $accessToken): array
    {
        return array_replace_recursive(parent::{__FUNCTION__}($accessToken), [
            'query' => [
                'prettyPrint' => 'false',
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected static function transformUserResponseToObject(array $user): UserContract {
        $avatarUrl = $user['image']['url'] ?? null;
        $avatarUrlWithoutSz = preg_replace('/\?sz=([0-9]+)/', '', $avatarUrl);

        return (new User($user))
            ->setFields([
                'id' => $user['id'],
                'nickname' => $user['nickname'] ?? null,
                'name' => $user['displayName'],
                'email' => $user['emails']['0']['value'] ?? null,
                'avatar' => $avatarUrl
            ])->setAdditionalFields([
                'avatar_original' => $avatarUrl ? $avatarUrlWithoutSz : null
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected static function getAccessTokenFields(string $code): array
    {
        return array_replace_recursive(parent::{__FUNCTION__}($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

}
