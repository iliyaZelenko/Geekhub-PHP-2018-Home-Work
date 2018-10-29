<?php

namespace IlyaZelen\UserOauth2Services\User;

use IlyaZelen\UserOauth2Services\Exceptions\RedHouseException;
use IlyaZelen\UserOauth2Services\Contracts\UserContract;

class User implements UserContract
{
    // ошибка поля
    const ERROR_FIELD = 'Данного поля нет в стандартных полях объекта, не стандартные надо указывать через setAdditionalFields';

    /**
     * Уникальный идентификатор в сервисе.
     * Может быть строкой или числом.
     *
     * @var string|int
     */
    protected $id;

    /**
     * nickname / username.
     *
     * @var string
     */
    protected $nickname;

    /**
     * Полное имя.
     *
     * @var string
     */
    protected $name;

    /**
     * E-mail пользователя.
     *
     * @var string
     */
    protected $email;

    /**
     * Аватарка.
     *
     * @var string
     */
    protected $avatar;

    /**
     * Оригинальный массив.
     *
     * @var array
     */
    protected $userOriginal;

    /**
     * Токен доступа.
     *
     * @var string
     */
    public $accessToken;

    /**
     * Токен для обновления токена.
     *
     * @var string
     */
    public $refreshToken;

    /**
     * Дополнительные поля.
     *
     * @var array
     */
    public $additionalFields = [];

    /**
     * Сколько секунд валиден токен.
     *
     * @var int
     */
    public $expiresIn;

    /**
     * Конструктор User.
     *
     * @param array $userOriginal
     */
    public function __construct(array $userOriginal)
    {
        // ставит оригинального пользователя
        $this->userOriginal = $userOriginal;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginal()
    {
        return $this->userOriginal;
    }

    /**
     * {@inheritdoc}
     */
    public function setFields(array $fields)
    {
        foreach ($fields as $key => $value) {
//            isset($this->{$key}
            if (! property_exists($this, $key) ) {
                throw new RedHouseException(static::ERROR_FIELD);
            }

            $this->$key = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setAdditionalFields(array $fields)
    {
        foreach ($fields as $key => $value) {
            $this->additionalFields[$key] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken(string $accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRefreshToken(string $refreshToken = null)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setExpiresIn(int $expiresIn = null)
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }
}
