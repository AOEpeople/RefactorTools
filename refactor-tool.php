#!/usr/bin/env php
<?php

$composerAutoloader = __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'autoload.php';
if (!file_exists($composerAutoloader)) {
    exit(
        PHP_EOL . 'This script requires the autoloader file created at install time by Composer. Looked for "' .
        $composerAutoloader . '" without success.'
    );
}
require $composerAutoloader;

use Aoe\RefactorTool\Console\Command;

$application = new \Symfony\Component\Console\Application();
$application->add(new Command\AutoloadingCommand());
$application->run();
