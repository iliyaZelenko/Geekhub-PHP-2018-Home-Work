<?php

namespace App\Form\DataObjects;

use Symfony\Component\Validator\Constraints as Assert;

final class RegistrationData
{
    // Допускаются русские символы (почему никнейм обязательно должен быть на английском?).
    /**
     * @Assert\Regex("/^[а-яА-Яa-zA-ZЁё][а-яА-Яa-zA-Z0-9Ёё]*?([-_.][а-яА-Яa-zA-Z0-9Ёё]+){0,3}$/u")
     */
    private $username;

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    // TODO если нужен перевод, то передавать message="author.name.not_blank"
    // https://symfony.com/doc/current/validation/translations.html
    /**
     * @Assert\NotBlank(message="Please enter a password")
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Your password should be at least {{ limit }} characters",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $plainPassword;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
