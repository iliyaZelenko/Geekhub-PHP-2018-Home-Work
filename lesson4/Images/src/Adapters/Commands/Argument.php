<?php

namespace IlyaZelen\Adapters\Commands;

class Argument
{
    protected const ERROR_UNSUPPORTED_TYPE = 'Тип %s не поддерживается.';

    /**
     * Привязанная команда
     *
     * @var AbstractCommand
     */
    public $command;

    /**
     * Идекс элемента массива аргументов
     *
     * @var int
     */
    protected $index;

    protected $type;

    /**
     * Значение по умолчанию
     *
     * @var mixed|null
     */
    public $default;

    public function __construct(AbstractCommand $command, int $index = 0)
    {
        $this->command = $command;
        $this->index = $index;
    }

    /**
     * Возвращает значение аргумента
     */
    public function value()
    {
        $arguments = $this->command->arguments;

        // если аргумент отсуствует
        if (!isset($arguments[$this->index])) {
            // вернуть значение по умолчанию если есть
            // важно именно isset (ide не то подсказывает)
            if (property_exists($this, 'default')) {
                return $this->default;
            }

            throw new \InvalidArgumentException(
                sprintf(
                    'Отсутствует обязательный аргумент %d для %s.',
                    $this->index + 1,
                    $this->command->getCommandName()
                )
            );
        }
        $value = $arguments[$this->index];

        $this->checkType($value);


        return $value;
    }

    /**
     * Ставит значение по умолчанию для аргумента, это также значит что аргумент становится не обязательным.
     *
     * @param mixed $value
     * @return Argument
     */
    public function default($value): Argument
    {
        $this->default = $value;

        return $this;
    }

    /**
     * Ставит тип аргументу
     *
     * @param string $type
     * @return Argument
     */
    public function type(string $type): Argument
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Проверяет тип аргумента
     *
     * @param mixed $value
     * @return void
     */
    protected function checkType($value): void
    {
        if ($value === null) return;

        switch (strtolower($this->type)) {
            case 'bool':
            case 'boolean':
                $valid = \is_bool($value);
                $message = '%s принимает только boolean значения как аргумент %d.';
                break;
            case 'int':
            case 'integer':
                $valid = \is_int($value);
                $message = '%s принимает только integer значения как аргумент %d.';
                break;
            case 'num':
            case 'numeric':
                $valid = is_numeric($value);
                $message = '%s принимает только numeric значения как аргумент %d.';
                break;
            case 'float':
                $valid = \is_float($value);
                $message = '%s принимает только float значения как аргумент %d.';
                break;
            case 'str':
            case 'string':
                $valid = \is_string($value);
                $message = '%s принимает только string значения как аргумент %d.';
                break;
            case 'arr':
            case 'array':
                $valid = \is_array($value);
                $message = '%s принимает только array значения как аргумент %d.';
                break;
            case 'closure':
                $valid = is_a($value, \Closure::class);
                $message = '%s принимает только Closure значения как аргумент %d.';
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf(static::ERROR_UNSUPPORTED_TYPE, $this->type)
                );
        }

        if (!$valid) {
            $commandName = $this->command->getCommandName();
            $argument = $this->index + 1;

            $message = sprintf($message, $commandName, $argument);

            throw new \InvalidArgumentException(
                $message
            );
        }
    }
}
