<?php

namespace IlyaZelen\UserOauth2Services\Contracts;

interface ProviderContract
{
    /**
     * Инициализация провайдера.
     * По желанию каждый провайдер может переопределить этот метод чтобы поставить свою логику на этапе инициализации.
     *
     * @param array $providerConfig
     * - 'name' => (string) Имя провайдера.
     * - 'clientId' => (string) Id клиента.
     * - 'clientSecret' => (string) Секретный ключ клиента.
     * - 'redirectUrl' => (string) Адрес перенаправления клиента.
     *
     *    $providerConfig = [
     *      'name' => (string) Имя провайдера.
     *      'clientId' => (string) Id клиента.
     *      'clientSecret' => (string) Секретный ключ клиента.
     *      'redirectUrl' => (string) Адрес перенаправления клиента.
     *    ]
     *
     * @param array $providerSettings
     * - 'guzzleOptions' => (array) Настройки guzzle клиента.
     * - 'additionalAuthUrlQueryParams' => (array) Дополнительные GET параметры для URL аутентификации.
     *
     *    $providerSettings = [
     *      'guzzleOptions' => (array) Настройки guzzle клиента.
     *      'additionalAuthUrlQueryParams' => (array) Дополнительные GET параметры для URL аутентификации.
     *    ]
     *
     */
    public static function init(array $providerConfig, array $providerSettings = []): void;

    /**
     * Перенаправляет пользователя на страницу аутентификации провайдера.
     * Использутся для stateful.
     *
     * @return void
     */
    public static function redirect(): void;

    /**
     * Используя code получает данные токена, после чего получает пользователя по токену доступа.
     *
     * @return UserContract
     */
    public static function user(): UserContract;

    /**
     * Получает пользователя по токену.
     *
     * @param string $accessToken
     * @param null $refreshToken
     * @param null $expiresIn
     * @return UserContract объект пользователя
     */
    public static function getUserByToken(string $accessToken, $refreshToken = null, $expiresIn = null): UserContract;

    /**
     * Сливает скопы доступа с базовыми.
     *
     * @param  array $additionalScopes нужные скопы.
     * @return string __CLASS__
     */
    public static function scopes(array $additionalScopes): string;

    /**
     * Получает ответ запроса на токен доступа по коду $code.
     *
     * @param string $code
     * @return array json_decode ответа в виде ассоциативного массива
     */
    public static function getAccessTokenResponse(string $code): array;

    /**
     * Возвращает URL страница аутентификации для провайдера.
     * Можно использовать на фронтеде получая через REST API.
     *
     * @return string
     */
    public static function getAuthUrl(): string;
}
