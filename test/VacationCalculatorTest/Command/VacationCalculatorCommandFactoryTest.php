<?php
namespace VacationCalculatorest\Command;

use VacationCalculator\Calculator\VacationCalculator;
use VacationCalculator\Command\VacationCalculatorCommand;
use VacationCalculator\Command\VacationCalculatorCommandFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class VacationCalculatorCommandFactoryTest extends TestCase
{
    public function testInvokeReturnsCommandInstance()
    {
        $vacationCalculator = $this->prophesize(VacationCalculator::class)->reveal();
        $entityManager = $this->prophesize(EntityManagerInterface::class)->reveal();
        $logger = $this->prophesize(LoggerInterface::class)->reveal();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(VacationCalculator::class)->willReturn($vacationCalculator);
        $container->get(EntityManager::class)->willReturn($entityManager);
        $container->get(Logger::class)->willReturn($logger);

        $factory = new VacationCalculatorCommandFactory();
        $result = $factory($container->reveal());

        $this->assertInstanceOf(VacationCalculatorCommand::class, $result);
    }
}
