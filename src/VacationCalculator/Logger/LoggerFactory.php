<?php

namespace VacationCalculator\Logger;

use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class LoggerFactory
 *
 * @package VacationCalculator
 */
class LoggerFactory
{
    /**
     * Creates logger instance
     *
     * @param ContainerInterface $container
     *
     * @return Logger
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        $logger = new Logger('vacation-calculator');
        $config = $container->get('config');

        $logger->pushHandler(new StreamHandler(
            $config['logger']['vacation-calculator']['file'],
            $config['logger']['vacation-calculator']['level'])
        );

        return $logger;
    }
}
