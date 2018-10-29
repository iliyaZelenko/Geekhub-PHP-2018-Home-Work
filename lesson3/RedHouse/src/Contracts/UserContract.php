<?php

namespace IlyaZelen\UserOauth2Services\Contracts;

use IlyaZelen\UserOauth2Services\Exceptions\RedHouseException;

interface UserContract extends UserTokenContract {

    /**
     * Возвращает id.
     *
     * @return string
     */
    public function getId();

    /**
     * Возвращает nickname.
     *
     * @return string
     */
    public function getNickname();

    /**
     * Возвращает name.
     *
     * @return string
     */
    public function getName();

    /**
     * Возвращает email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Возвращает avatar.
     *
     * @return string
     */
    public function getAvatar();

    /**
     * Возвращает оригинального пользовтаеля.
     *
     * @return array
     */
    public function getOriginal();

    /**
     * Ставит стандартные поля в объект.
     *
     * @param  array $fields
     * @return $this
     * @throws RedHouseException
     */
    public function setFields(array $fields);

    /**
     * Ставит дополнительные поля в объект.
     *
     * @param  array $fields
     * @return $this
     */
    public function setAdditionalFields(array $fields);
}
