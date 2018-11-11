<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait OutputArgumentsTrait
{
    public function setArguments(): void
    {
        $this->defineArgument('format', 'str');

        $this
            ->defineArgument('compression', 'int')
            ->default(65);
    }
}
