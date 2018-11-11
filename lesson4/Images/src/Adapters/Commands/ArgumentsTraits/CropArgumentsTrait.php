<?php

namespace IlyaZelen\Adapters\Commands\ArgumentsTraits;

trait CropArgumentsTrait
{
    public function setArguments(): void
    {
        $this->defineArgument('x', 'int');
        $this->defineArgument('y', 'int');
        $this->defineArgument('width', 'int');
        $this->defineArgument('height', 'int');
    }
}
