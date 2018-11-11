<?php

namespace IlyaZelen\Adapters\Commands;

use IlyaZelen\Adapters\AbstractAdapter;

abstract class AbstractCommand
{
    public $arguments;

    /**
     * @var AbstractAdapter
     */
    protected $driver;
    protected $image;
    protected $lastArgumentIndex = 0;

    /**
     * @var array Именованные аргументы
     */
    protected $definedArguments = [];

    abstract public function execute(&$image, AbstractAdapter $driver);


    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Объявляет аргумент
     *
     * @param string $type
     * @return Argument
     */
    public function defineArgument(string $name, string $type): Argument
    {
        return $this->definedArguments[$name] = (
            new Argument($this, $this->lastArgumentIndex++)
        )->type($type);
    }

    /**
     * Возвращает объвленный аргумент
     *
     * @param string $key
     * @return mixed
     */
    public function argument(string $key)
    {
        return $this->definedArguments[$key]->value();
    }

    /**
     * Возвращает имя текущей команды
     *
     * @return string
     */
    public function getCommandName(): string
    {
        preg_match("/\\\\([\w]+)Command$/", static::class , $matches);

        return isset($matches[1]) ? lcfirst($matches[1]) . '()' : 'Метод';
    }
}
