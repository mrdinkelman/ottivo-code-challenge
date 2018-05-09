<?php

namespace VacationCalculator\Calculator;

use Interop\Container\ContainerInterface;

class VacationCalculatorFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return VacationCalculator
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        return new VacationCalculator($container->get(Config::class));
    }
}
