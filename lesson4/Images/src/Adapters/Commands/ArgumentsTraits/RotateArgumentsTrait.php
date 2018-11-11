<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait RotateArgumentsTrait
{
    public function setArguments(): void
    {
        $this->defineArgument('angle', 'int');

        $this
            ->defineArgument('backgroundColor', 'str')
            ->default(null);

        $this
            ->defineArgument('crop', 'bool')
            ->default(false);
    }
}
