<?php

namespace IlyaZelen\Command\Commands;

use IlyaZelen\Command\Image;

class ResizeCommand extends AbstractCommand
{
    public function execute(Image $image)
    {
        $width = $this->getArgument(0);
        $height = $this->getArgument(0);

        echo "Resize to width $width px and height $height px.\n";
    }
}
