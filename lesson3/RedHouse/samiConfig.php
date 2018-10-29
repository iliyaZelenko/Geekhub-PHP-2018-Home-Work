<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
//    ->exclude('Resources')
//    ->exclude('Tests')
    ->in( __DIR__ . '/src')
;

return new Sami($iterator, array(
//    'theme'                => 'symfony',
    'title'                => 'RedHouse',
    'build_dir'            => __DIR__ . '/docs-build',
    'cache_dir'            => __DIR__ . '/docs-cache',
//    'remote_repository'    => new GitHubRemoteRepository('username/repository', '/path/to/repository'),
    'default_opened_level' => 2,
));
