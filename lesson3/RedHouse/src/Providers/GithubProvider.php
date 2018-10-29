<?php

namespace IlyaZelen\UserOauth2Services\Providers;

use IlyaZelen\UserOauth2Services\Contracts\ProviderContract;
use IlyaZelen\UserOauth2Services\Contracts\UserContract;
use IlyaZelen\UserOauth2Services\User\User;

class GithubProvider extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    protected static function getAuthUrlBase(): string
    {
        return 'https://github.com/login/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getUserUrl(string $accessToken): string
    {
        return 'https://api.github.com/user?access_token=' . $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    protected static function getAccessTokenUrl(): string
    {
        return 'https://github.com/login/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     *
     * @see supported scopes: https://developer.github.com/apps/building-oauth-apps/understanding-scopes-for-oauth-apps/
     */
    protected static function getScopes(): array
    {
        // supported scopes: https://developer.github.com/apps/building-oauth-apps/understanding-scopes-for-oauth-apps/
        return ['user:email'];
    }

    /**
     * {@inheritdoc}
     */
    protected static function getUserByAccessTokenResponseRequestOptions(string $accessToken): array
    {
        return array_replace_recursive(parent::{__FUNCTION__}($accessToken), [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected static function getModifiedUserByAccessTokenResponse(array $response, string $accessToken): array
    {
        // email приходится получать отдельно
        $response['emails'] = static::getEmailsByAccessToken($accessToken);

        foreach ($response['emails'] as $email) {
            // если email главный и он подтвержденный
            if ($email['primary'] && $email['verified']) {
                $response['email'] =  $email['email'];
            }
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected static function transformUserResponseToObject(array $user): UserContract {
        return (new User($user))
            ->setFields([
                'id' => $user['id'],
                'nickname' => $user['login'],
                'name' => $user['name'] ?? null,
                'email' => $user['email'] ?? null,
                'avatar' => $user['avatar_url']
            ])->setAdditionalFields([
//                'avatar_original' => $avatarUrl ? $avatarUrlWithoutSz : null
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected static function getAccessTokenFields(string $code): array
    {
        return array_replace_recursive(parent::getAccessTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    /**
     * Получает электронные адреса по токену (в гитхабе почему не могут сразу дать).
     *
     * @param  string $accessToken
     * @return array
     */
    protected static function getEmailsByAccessToken(string $accessToken): array
    {
        $emailsUrl = 'https://api.github.com/user/emails?access_token=' . $accessToken;

        try {
            $response = static::getHttpClient()->get(
                $emailsUrl, static::getUserByAccessTokenResponseRequestOptions($accessToken)
            );
        } catch (\Exception $e) {
            return [];
        }

        return json_decode($response->getBody(), true);
    }
}
