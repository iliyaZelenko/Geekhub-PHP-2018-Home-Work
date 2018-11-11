<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait NewArgumentsTrait
{
    public function setArguments(): void
    {
        $this->defineArgument('width', 'integer');

        $this->defineArgument('height', 'integer');

        $this
            ->defineArgument('backgroundColor', 'str')
            ->default(null);
    }
}
