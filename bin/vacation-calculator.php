<?php

/**
 * Script for providing information about employees and their vacation days
 *
 * Can also be invoked as `composer employee`.
 */

chdir(__DIR__ . '/../');

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Psr\Container\ContainerExceptionInterface;

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

$application = new Application('Vacation Calculator CLI');

try {
    $commands = $container->get('config')['cli']['commands'];

    foreach ($commands as $command) {
        $application->add($container->get($command));
    }

    $application->run();

} catch (ContainerExceptionInterface $exception) {
    die(sprintf("Container exception has been occurred due executing CLI command: %s\n", $exception->getMessage()));

} catch (Exception $exception) {
    die(sprintf("Exception has been occurred due executing CLI command: %s\n", $exception->getMessage()));

}
