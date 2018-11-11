<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait SaveArgumentsTrait
{
    public function setArguments(): void
    {
        $this->defineArgument('path', 'str');
    }
}
