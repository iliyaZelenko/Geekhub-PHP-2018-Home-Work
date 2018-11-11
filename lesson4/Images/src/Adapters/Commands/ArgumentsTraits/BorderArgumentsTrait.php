<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait BorderArgumentsTrait
{
    public function setArguments(): void
    {
        $this
            ->defineArgument('color', 'str')
            ->default('black');

        $this
            ->defineArgument('thickness', 'int')
            ->default(1);
    }
}
