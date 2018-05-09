<?php

namespace VacationCalculator\Calculator;

use Interop\Container\ContainerInterface;

/**
 * Class ConfigFactory
 *
 * @package VacationCalculator\Calculator
 */
class ConfigFactory
{
    /**
     * Returns Config instance
     *
     * @param ContainerInterface $container
     *
     * @return Config
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        if (!array_key_exists('employee', $config)) {
            throw new \RuntimeException('Employee config is not set');
        }

        return new Config($config['employee']);
    }
}
