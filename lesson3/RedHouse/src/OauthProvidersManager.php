<?php

namespace IlyaZelen\UserOauth2Services;

use IlyaZelen\UserOauth2Services\Providers\{FacebookProvider, GithubProvider, GoogleProvider};

/**
 * Главный класс библиотеки.
 * @package IlyaZelen\UserOauth2Services
 */
class OauthProvidersManager
{
    // список ошибок
    const ERROR_STORE_CONTRACT = 'Вы передали конфиг для провайдера который не поддерживается.';
    const ERROR_SET_PROVIDERS_CONFIGS = 'Вы передали конфиг для провайдера который не поддерживается.';
    const ERROR_PROVIDER_SUPPORT = 'Этот провайдер не поддерживается.';
    const ERROR_PROVIDER_CONFIG = 'Вы не предоставили конфиг для этого провайдера.';

    /**
     * Драйвер хранилища по умолчанию.
     *
     * @var string
     */
    protected static $storeDefaultDriver = 'session';

    /**
     * Доступные драйвера OAuth.
     *
     * @var array
     */
    protected static $providers = [
        'google' => GoogleProvider::class,
        'github' => GithubProvider::class,
        'facebook' => FacebookProvider::class,
    ];

    /**
     * Конфиги провайдеров.
     *
     * @var array
     */
    protected static $providersConfigs;

    /**
     * Настрйоки пакета.
     *
     * @var array
     */
    public static $settings = [];

    /**
     * Инициализирует пакет.
     *
     * @param array $providersConfigs конфиги провайдеров
     * @param array $settings главные настройки библиотеки
     * @return void
     */
    public static function init(array $providersConfigs, array $settings): void
    {
        static::$settings = $settings;

        // ставит конфиги дял првоайдеров
        static::setProvidersConfigs($providersConfigs);
    }

    /**
     * Проверяет поддержку провайдера и инициализирует.
     * используя конфиг заданный в <u>RedHouse::init()</u> и настройки $providerSettings.
     *
     * @param string $provider имя провайдера
     * @param array $providerSettings настройки провайдера
     *
     * @throws \InvalidArgumentException если провайдер $provider не поддерживается или не предоставлен конфиг.
     *
     * @return string строка класса провайдера наследующего от AbstractProvider
     */
    public static function provider(string $provider, array $providerSettings = []): string
    {
        if (! isset(static::$providers[$provider])) {
            throw new \InvalidArgumentException(static::ERROR_PROVIDER_SUPPORT);
        }
        if (! $providerConfig = static::getProviderConfig($provider)) {
            throw new \InvalidArgumentException(static::ERROR_PROVIDER_CONFIG);
        }

        // класс провайдера
        $providerClass = static::getProvider($provider);
        // инициализация провайдера
        $providerClass::init($providerConfig, $providerSettings);

        return $providerClass;
    }

    /**
     * Ставит конфиги для провайдеров.
     *
     * @throws \InvalidArgumentException если передать конфиг для провайдера который не поддерживается.
     *
     * @param array $providersConfigs
     * @return void
     */
    protected static function setProvidersConfigs(array $providersConfigs): void
    {
        foreach ($providersConfigs as $config) {
            if (! isset(static::$providers[$config['name']])) {
                throw new \InvalidArgumentException(static::ERROR_SET_PROVIDERS_CONFIGS);
            }
        }

        static::$providersConfigs = $providersConfigs;
    }

    /**
     * Возвращает класс провайдера.
     *
     * @param string $provider
     * @return string
     */
    protected static function getProvider(string $provider): string
    {
        return static::$providers[$provider];
    }

    /**
     * Возвращает конфиг провайдера.
     *
     * @param string $provider
     * @return array|null
     */
    protected static function getProviderConfig(string $provider): ?array
    {
        // было бы удобно если бы в php была функция на подобии first в JS.
        $found = \array_filter(static::$providersConfigs, function (array $config) use ($provider) {
            return $config['name'] === $provider;
        });

        if (! count($found)) {
            return null;
        }

//        var_dump(current($found));

        return current($found);
    }
}

