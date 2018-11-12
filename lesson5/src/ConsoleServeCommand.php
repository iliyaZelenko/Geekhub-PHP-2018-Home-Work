<?php

namespace IlyaZelen;

// https://getcomposer.org/apidoc/1.5.6/Composer/Script/Event.html
use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class ConsoleServeCommand
{
    public static function myCallback(Event $event) {
        $patternName = $event->getArguments()[0];

        echo shell_exec('php -S localhost:8000 -t src/' . $patternName);
    }
}
