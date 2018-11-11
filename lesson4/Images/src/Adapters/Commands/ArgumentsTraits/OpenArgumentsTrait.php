<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait OpenArgumentsTrait
{
    public function setArguments(): void
    {
        $this->defineArgument('path', 'str');
    }
}
