<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait TextArgumentsTrait
{
    public function setArguments()
    {
        $this
            ->defineArgument('text', 'str');

        $this
            ->defineArgument('x', 'num')
            ->default(0);

        $this
            ->defineArgument('y', 'num')
            ->default(0);

        $this
            ->defineArgument('color', 'str')
            ->default('black');

        $this
            ->defineArgument('font', 'str')
            ->default(null);

        $this
            ->defineArgument('size', 'int')
            ->default(20);

        $this
            ->defineArgument('angle', 'int')
            ->default(0);
    }
}
