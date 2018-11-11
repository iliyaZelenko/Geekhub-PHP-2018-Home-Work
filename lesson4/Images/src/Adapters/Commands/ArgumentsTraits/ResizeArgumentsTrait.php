<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait ResizeArgumentsTrait
{
    public function setArguments(): void
    {
        $this
            ->defineArgument('newWidth', 'num')
            ->default(null);
        $this
            ->defineArgument('newHeight', 'num')
            ->default(null);
    }
}
