<?php

namespace IlyaZelen\Command;

class Image
{
    public function __call($name, $arguments)
    {
        $commandName = $this->getCommandClassName($name);

        $command = new $commandName($arguments);
        $output = $command->execute($this);

        return $output ?? $this;
    }

    private function getCommandClassName($name)
    {
        $class = sprintf('IlyaZelen\Command\Commands\%sCommand', ucfirst($name));

        if (class_exists($class)) return $class;

        throw new \InvalidArgumentException(
            "Command \"{$name}\" is not available!."
        );
    }
}
