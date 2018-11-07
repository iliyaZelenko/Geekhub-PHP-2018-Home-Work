<?php

namespace IlyaZelen\Command\Commands;

use IlyaZelen\Command\Image;

class SaveCommand extends AbstractCommand
{
    public function execute(Image $image)
    {
        $path = $this->getArgument(0);

        echo "Image saved to $path.\n";

        return $path;
    }
}
