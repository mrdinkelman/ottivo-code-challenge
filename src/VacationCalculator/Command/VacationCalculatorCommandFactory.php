<?php

namespace VacationCalculator\Command;

use VacationCalculator\Calculator\VacationCalculator;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Monolog\Logger;

class VacationCalculatorCommandFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return VacationCalculatorCommand
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        return new VacationCalculatorCommand(
            $container->get(VacationCalculator::class),
            $container->get(EntityManager::class),
            $container->get(Logger::class)
        );
    }
}