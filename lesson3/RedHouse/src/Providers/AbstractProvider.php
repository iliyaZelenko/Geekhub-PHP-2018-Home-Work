<?php

namespace IlyaZelen\UserOauth2Services\Providers;

use IlyaZelen\UserOauth2Services\Exceptions\RedHouseException;
use IlyaZelen\UserOauth2Services\OauthProvidersManager;
use IlyaZelen\UserOauth2Services\Contracts\ProviderContract;
use IlyaZelen\UserOauth2Services\Contracts\UserContract;
use GuzzleHttp\Client;

abstract class AbstractProvider implements ProviderContract
{
    // список ошибок
    const ERROR_PROVIDER = 'Ошибка провайдера, смотрите параметр "error".';
    const ERROR_CODE = 'Не удалось получить параметр "code".';



    // которые провайдер обязан ставить сам (методы имеют abstract):
    // TODO сделать что эти методы внутренними и сделать чтобы была возможность указать более коротко через свойства класса, а если надо динамически, то можно объявить свойство
    /**
     * Возвращает базовый URL аутентификации провайдера. К нему будет добавлены GET параметры.
     *
     * @return string
     */
    abstract protected static function getAuthUrlBase(): string; // функции для большой гибкости если надо получить данные динамично

    /**
     * URL для получения токена доступа.
     *
     * @return string
     */
    abstract protected static function getAccessTokenUrl(): string;

    /**
     * URL для получения пользователя.
     *
     * @param string $accessToken
     * @return string
     */
    abstract protected static function getUserUrl(string $accessToken): string;

    /**
     * Скопы доступа (к чему будет иметь разрешение) провайдера.
     *
     * @return array
     */
    abstract protected static function getScopes(): array;

    /**
     * Получить ответ запроса на получения пользователя по токену доступа.
     *
     * @param string $accessToken
     * @return array
     */
//    abstract protected static function getUserByAccessTokenResponse(string $accessToken): array;

    /**
     * Трансформирует ответ запроса пользователя в объект.
     *
     * @param array $userResponse
     * @return UserContract
     */
    abstract protected static function transformUserResponseToObject(array $userResponse): UserContract;


    /**
     * Разделитель скопа требующийся в запросе.
     *
     * @var string
     */
    protected static $scopeSeparator = ' ';



    // которые записуются самим классом:
    /**
     * Конфиг провайдера.
     *
     * @var array
     */
    protected static $providerConfig;

    /**
     * Настройки провайдера.
     *
     * @var array
     */
    protected static $providerSettings;

    /**
     * Http клиент для запросов. Используется для получения токена и пользователя.
     *
     * @var Client
     */
    protected static $httpClient;

    /**
     * Дополнительные скопы доступа которые сливаются с базовыми.
     *
     * @var array
     */
    protected static $additionalScopes;



    /**
     * {@inheritdoc}
     */
    public static function init(array $providerConfig, array $providerSettings = []): void
    {
        static::$providerConfig = $providerConfig;
        static::$providerSettings = $providerSettings;
    }

    /**
     * {@inheritdoc}
     */
    public static function redirect(): void
    {
        header('Location: ' . static::getAuthUrl());
        die();
    }

    /**
     * {@inheritdoc}
     */
    public static function user(): UserContract
    {
        $code = static::getCode();
        $tokenResponse = static::getAccessTokenResponse($code);
        $accessToken = $tokenResponse['access_token'];

        return static::getUserByToken(
            $accessToken,
            $tokenResponse['refresh_token'] ?? null,
            $tokenResponse['expires_in'] ?? null
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getUserByToken(string $accessToken, $refreshToken = null, $expiresIn = null): UserContract
    {
        $userResponse = static::getUserByAccessTokenResponse($accessToken);
        $userObject = static::transformUserResponseToObject($userResponse);

        return $userObject
            ->setToken($accessToken)
            ->setRefreshToken($refreshToken)
            ->setExpiresIn($expiresIn);
    }

    /**
     * {@inheritdoc}
     */
    public static function scopes(array $additionalScopes): string
    {
        static::$additionalScopes = $additionalScopes;

        return static::class;
    }

    /**
     * {@inheritdoc}
     */
    public static function getAccessTokenResponse(string $code): array
    {
        $endpoint = static::getAccessTokenUrl();
        $response = static::getHttpClient()->post(
            $endpoint,
            array_merge(static::getAccessTokenResponseRequestOptions($code), [
                'form_params' => static::getAccessTokenFields($code)
            ])
        );
        $responseArr = json_decode($response->getBody(), true);

        return static::getModifiedAccessTokenResponse($responseArr);
    }

    /**
     * {@inheritdoc}
     */
    public static function getAuthUrl(): string
    {
        return static::getAuthUrlBase() . '?' . http_build_query(
            static::getAuthUrlQueryParams(), '', '&'
        );
    }

    /**
     * Возвращает опции запроса получения токена доступа.
     *
     * @param string $code
     * @return array
     */
    protected static function getAccessTokenResponseRequestOptions(string $code): array {
        return [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
    }

    /**
     * Возвращает опции запроса получения пользователя по токену доступа.
     *
     * @param string $accessToken
     * @return array
     */
    protected static function getUserByAccessTokenResponseRequestOptions(string $accessToken): array {
        return [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
    }

    /**
     * Получает пользователю используя ответ запроса на получения токена доступа.
     *
     * @param string $accessToken
     * @return array
     */
    protected static function getUserByAccessTokenResponse(string $accessToken): array
    {
        $response = static::getHttpClient()->get(
            static::getUserUrl($accessToken),
            static::getUserByAccessTokenResponseRequestOptions($accessToken)
        );
        $responseArr = json_decode($response->getBody(), true);

        return static::getModifiedUserByAccessTokenResponse($responseArr, $accessToken);
    }

    /**
     * Благодаря этому методу, провайдер может легко модифицировать ответ запроса на получение токена.
     *
     * @param $response
     * @return array
     */
    protected static function getModifiedAccessTokenResponse(array $response): array
    {
        return $response;
    }

    /**
     * Благодаря этому методу, провайдер может легко модифицировать ответ запроса на получение пользователя.
     *
     * @param array $response
     * @param string $accessToken
     * @return array
     */
    protected static function getModifiedUserByAccessTokenResponse(array $response, string $accessToken): array
    {
        return $response;
    }

    /**
     * Возвращает GET параметр code из запроса.
     *
     * @return string - параметр code
     * @throws RedHouseException если провайдер выдал ошибку или если нет параметра "code".
     */
    protected static function getCode(): string
    {
        if (isset($_GET['error'])) {
            throw new RedHouseException(static::ERROR_PROVIDER);
        }
        if (! isset($_GET['code'])) {
            throw new RedHouseException(static::ERROR_CODE);
        }

        return $_GET['code'];
    }

    /**
     * Возвращает GET параметры для URL аутентификации.
     *
     * @return array
     */
    protected static function getAuthUrlQueryParams(): array
    {
        $fields = [
            'client_id' => static::$providerConfig['clientId'],
            'redirect_uri' => static::$providerConfig['redirectUrl'],
            'scope' => static::formatScopes(),
            'response_type' => 'code',
        ];

        return array_replace_recursive($fields, static::$providerSettings['additionalAuthUrlQueryParams'] ?? []);
    }

    /**
     * Форматирует скопы по разделителю.
     *
     * @return string
     */
    protected static function formatScopes(): string
    {
        return implode(static::$scopeSeparator,
            array_replace_recursive(static::getScopes(), static::$additionalScopes)
        );
    }

    /**
     * Получить параметры для POST запроса на получения токена.
     *
     * @param  string $code
     * @return array
     */
    protected static function getAccessTokenFields(string $code): array
    {
        return [
            'client_id' => static::$providerConfig['clientId'],
            'client_secret' => static::$providerConfig['clientSecret'],
            'redirect_uri' => static::$providerConfig['redirectUrl'],
            'code' => $code
        ];
    }

    /**
     * Возвращает экземпляр Guzzle HTTP клиента.
     *
     * @return \GuzzleHttp\Client
     */
    protected static function getHttpClient(): Client
    {
        if (static::$httpClient === null) {
            static::$httpClient = new Client(static::getGuzzleOptions());
        }
        return static::$httpClient;
    }

    /**
     * Возвращает Guzzle опции (слияние глобальных пакета с локальными провайдера).
     *
     * @return array
     */
    protected static function getGuzzleOptions(): array
    {
        // объеденяет глобальные настройки с глобальными
        return array_replace_recursive(
            OauthProvidersManager::$settings['guzzleOptions'] ?? [],
            static::$providerSettings['guzzleOptions'] ?? []
        );
    }
}
