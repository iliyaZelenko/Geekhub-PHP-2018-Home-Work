<?php

namespace IlyaZelen\Command\Commands;

use IlyaZelen\Command\Image;

abstract class AbstractCommand
{
    /**
     * Аргументы.
     *
     * @var array
     */
    public $arguments;

    /**
     * Вызывает команду.
     *
     * @param Image $image
     * @return mixed
     */
    abstract public function execute(Image $image);

    /**
     * Создает новый аргумент объекта.
     *
     * @param array $arguments
     */
    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Возвращает аргумент по заданмоу индексу или значение по умолчанию.
     *
     * @param int $index
     * @param null $default
     * @return mixed|null
     */
    public function getArgument($index = 0, $default = null)
    {
        $arguments = $this->arguments;
        if (\is_array($arguments)) {
            return $arguments[$index] ?? $default;
        }

        return $default;
    }
}
