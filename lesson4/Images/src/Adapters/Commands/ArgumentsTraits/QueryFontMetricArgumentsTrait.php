<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait QueryFontMetricArgumentsTrait
{
    public function setArguments(): void
    {
        $this
            ->defineArgument('text', 'str');

        $this
            ->defineArgument('size', 'int');

        $this
            ->defineArgument('font', 'string')
            ->default(null);

        $this
            ->defineArgument('angle', 'int')
            ->default(0);
    }
}
