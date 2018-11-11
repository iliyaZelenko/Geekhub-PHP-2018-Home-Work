<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait FlipArgumentsTrait
{
    public function setArguments(): void
    {
        $this
            ->defineArgument('mode', 'str')
            ->default('vertical');
    }
}
