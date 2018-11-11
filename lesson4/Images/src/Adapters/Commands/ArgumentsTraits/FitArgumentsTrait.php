<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait FitArgumentsTrait
{
    public function setArguments(): void
    {
        $this
            ->defineArgument('boundaryWidth', 'int')
            ->default(null);
        $this
            ->defineArgument('boundaryHeight', 'int')
            ->default(null);
    }
}
